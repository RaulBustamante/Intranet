#!/usr/bin/env bash
#
# Vuelve la intranet a la v1. Correr como:  sudo bash v2/deploy/revertir.sh
#
# Funciona porque servidor-setup.sh nunca borro la v1: solo deshabilito su vhost.
# Tarda segundos y no toca la base de datos (la v2 usa arielhub_v2, la v1 usa
# arielhub; son bases distintas, asi que revertir no pierde nada de la v1).
#
set -euo pipefail

[ "$(id -u)" -eq 0 ] || { echo "ERROR: se necesita root. Usa: sudo bash $0"; exit 1; }

echo "Reactivando la v1..."
a2dissite -q intranet-v2.conf || true
a2ensite  -q intranet.conf
apache2ctl configtest
systemctl reload apache2

echo
echo "La v1 esta de vuelta en http://arielhub.arielapps.net/"
echo "La v2 sigue instalada en /var/www/html/intranet/v2, solo sin vhost activo."
