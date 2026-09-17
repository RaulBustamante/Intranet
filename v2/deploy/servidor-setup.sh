#!/usr/bin/env bash
#
# Despliegue de la Intranet v2 en arielhub (54.71.240.78).
#
# Requiere root. Correr como:   sudo bash v2/deploy/servidor-setup.sh
#
# DISENO: la v1 NO se toca ni se borra. Sigue en disco con su vhost intacto,
# solo deshabilitado. Volver atras son dos comandos (ver revertir.sh).
# La v1 sigue corriendo bajo mod_php 8.1; la v2 corre bajo php8.3-fpm. Conviven.
#
set -euo pipefail

APP=/var/www/html/intranet
V2=$APP/v2
DOMINIO=arielhub.arielapps.net

paso() { echo; echo "=====> $*"; }

[ "$(id -u)" -eq 0 ] || { echo "ERROR: se necesita root. Usa: sudo bash $0"; exit 1; }
[ -d "$V2" ] || { echo "ERROR: no existe $V2. Corre 'git pull' primero."; exit 1; }
[ -f "$V2/.env" ] || { echo "ERROR: falta $V2/.env (se sube aparte, no esta en git)."; exit 1; }
[ -d "$V2/public/build" ] || { echo "ERROR: faltan los assets compilados en $V2/public/build."; exit 1; }

paso "1/7 Swap de 2 GB"
# La maquina tiene 1.9 GB sin swap. composer install puede provocar que el OOM
# killer mate MySQL y tire la intranet. El swap es la red de seguridad.
if swapon --show | grep -q /swapfile; then
  echo "    ya existe, se deja como esta"
else
  fallocate -l 2G /swapfile
  chmod 600 /swapfile
  mkswap /swapfile
  swapon /swapfile
  grep -q '^/swapfile' /etc/fstab || echo '/swapfile none swap sw 0 0' >> /etc/fstab
  echo "    swap activado"
fi

paso "2/7 PHP 8.3: FPM y extensiones faltantes"
# Ya hay php8.3 CLI (8.3.11). Faltan intl, gd y bcmath, y no hay pool de FPM.
apt-get update -qq
apt-get install -y -qq \
  php8.3-fpm php8.3-intl php8.3-gd php8.3-bcmath \
  php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip
systemctl enable --now php8.3-fpm
echo "    php8.3-fpm arriba"

paso "3/7 Modulos de Apache para hablar con FPM"
a2enmod -q proxy_fcgi setenvif || true
# OJO: NO se desactiva mod_php8.1. La v1 lo sigue necesitando para poder revertir.

paso "4/7 Dependencias de PHP de la v2"
cd "$V2"
sudo -u raulb php8.3 /usr/local/bin/composer install \
  --no-dev --optimize-autoloader --no-interaction

paso "5/7 Permisos de escritura para Apache"
chown -R raulb:www-data "$V2/storage" "$V2/bootstrap/cache"
chmod -R 775 "$V2/storage" "$V2/bootstrap/cache"

paso "6/7 Migraciones y cache de la v2"
cd "$V2"
sudo -u raulb php8.3 artisan migrate --force
sudo -u raulb php8.3 artisan config:cache
sudo -u raulb php8.3 artisan route:cache
sudo -u raulb php8.3 artisan view:cache
sudo -u raulb php8.3 artisan storage:link || true

paso "7/7 Vhost de la v2 y corte"
cat > /etc/apache2/sites-available/intranet-v2.conf <<VHOST
<VirtualHost *:80>
    ServerName $DOMINIO
    DocumentRoot $V2/public

    <Directory $V2/public>
        Options FollowSymLinks
        AllowOverride All
        Require all granted

        # Esta v2 corre en PHP 8.3 via FPM, no en el mod_php 8.1 del resto
        <FilesMatch "\.php\$">
            SetHandler "proxy:unix:/run/php/php8.3-fpm.sock|fcgi://localhost"
        </FilesMatch>
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/intranet-v2-error.log
    CustomLog \${APACHE_LOG_DIR}/intranet-v2-access.log combined
</VirtualHost>
VHOST

apache2ctl configtest
a2dissite -q intranet.conf   # la v1 queda deshabilitada, NO borrada
a2ensite  -q intranet-v2.conf
systemctl reload apache2

echo
echo "================================================================"
echo " LISTO. La v2 responde en http://$DOMINIO/"
echo " La v1 sigue intacta en disco y su vhost solo esta deshabilitado."
echo " Para revertir:  sudo bash $V2/deploy/revertir.sh"
echo "================================================================"
