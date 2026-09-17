# Project State

## Project Reference

See: .planning/PROJECT.md (updated 2026-09-10)

**Core value:** Que un empleado encuentre lo que busca en menos de 10 segundos, y que RH pueda mantenerlo actualizado sin pedirle nada a TI.
**Current focus:** Phase 2 — Personas (Fases 0 y 1 parciales: lo que falta depende del servidor)

## Current Position

Phase: 3 of 7 (Calendario y Salas) — Fase 2 casi completa; Fases 0 y 1 abiertas en lo del servidor
Plan: Fases 3, 4 y 5 nucleo completo; Fase 2 en 5/6
Status: **In progress.** Lo que no depende del servidor esta hecho y verificado; lo que depende de el esta bloqueado
Last activity: 2026-09-11 — NUEVO MODELO DE ACCESO: navegar es publico (red interna), capturar exige sesion. Todo el contenido (inicio, directorio, calendario, boletines, documentos, enlaces, salas, solicitudes, kudos, busqueda) es navegable sin login; solo lo personal (mi-perfil, conexiones) y admin/* exigen sesion. El candado de captura (submit/book/send/vote) vive en cada componente y redirige al login sin bloquear la navegacion. Invitado ve boton "Iniciar sesion" y avisos discretos. Ademas: exportar directorio a CSV (PPL-09). 110 pruebas en verde. Antes: Enlaces con logos+color y 5 nuevos; Fase 6 Busqueda global (nucleo) con capa AI apagada por defecto; Fase 2 (CSV masivo, auto-perfil, fotos S3); calendario "estilo Teams" (cuentas vinculadas, apagadas por defecto). La v2 usa MySQL del servidor (arielhub_v2, separada de produccion)

Progress: [████████░░] ~80%

## Performance Metrics

**Velocity:**
- Total plans completed: ~24 (2 F0, 3 F1, 5 F2, 4 F3, 4 F4, 4 F5, 2 F6) + landing + shortcuts + fotos + encuestas
- Average duration: —
- Total execution time: ~1 sesión

**By Phase:**

| Phase | Plans | Completos | Nota |
|-------|-------|-----------|------|
| 0 | 3 | 2 | El tercero depende de acciones manuales de Raúl. R4 (versionado S3) hecho y verificado |
| 1 | 5 | 3 | Diseno, i18n y carcasa navegable hechos. Faltan 1.9 (servidor, bloqueado por sudo) y 1.5 (staging, bloqueado por DNS) |
| 3 | 4 | 4 | COMPLETA: motor de festivos (12 pruebas), calendario mes/lista, feed iCal, salas con OverlapGuard, reserva + mis reservas, correo .ics, CRUD eventos. Diferido sin bloquear: drag y recurrencia |
| 2 | 6 | 5 | Esquema, importadores, panel de RH, directorio con ficha/organigrama, y AUTENTICACION real con roles. Falta 02-06 (carga CSV masiva y fotos) |

**Recent Trend:**
- La ejecución de la Fase 0 agregó 6 requisitos nuevos (103 → 109). La planeación subestimó el estado del perímetro de seguridad, no el trabajo de desarrollo

*Updated after each plan completion*

## Accumulated Context

### Decisions

Registradas en PROJECT.md (Key Decisions) y en `asistente-ejecutivo-trabajo/decisions/log.md`.

Decisiones que condicionan el trabajo actual:

- Rebuild v2 en paralelo; la v1 no se toca salvo en Fase 0 y Fase 7
- Stack real instalado: **Laravel 13.31 + Livewire 4.4** + Tailwind 4 + MySQL 8 (el plan decia 12 y 3; el ecosistema avanzo y se acepto a proposito por ser proyecto nuevo). **Laravel 13 exige PHP ^8.3**, asi que el pool de FPM del servidor tiene que pasar de 8.1 a 8.3
- Usuarios propios, no SSO. La capa de auth debe admitir SSO después sin migrar usuarios
- La intranet es la fuente de verdad de empleados; sin integración HRIS
- Bilingüe ES/EN desde el arranque: ninguna cadena visible dentro de una vista
- Rojo `#ED2228` solo como acento; texto rojo usa `#C91B21` por contraste AA
- Solo mes y día de cumpleaños; nunca el año
- Festivos por reglas, nunca por fechas capturadas
- AI con drivers `claude`/`openai`/`null`, default `null`, cero costo hasta autorización
- **[Fase 0, nueva] HTTPS en la v1 se adelanta a la Fase 0.** No se puede esperar a la Fase 7 teniendo la contraseña compartida de 200 personas viajando en texto claro todos los días
- **[Fase 2] La v2 usa MySQL del servidor (arielhub_v2), no el XAMPP local** que se apagaba solo. Decision de Raul. Separada de la arielhub de produccion.
- **[Fase 0, abierta] Redis vs. base de datos para cache, sesión y cola.** Con 1.9 GB de RAM y sin swap, más MySQL, Apache y PHP-FPM en la misma máquina, Redis puede ser la decisión equivocada. Se resuelve en el plan 01-01 midiendo consumo real

### Pending Todos

Capturados durante la auditoría y la ejecución de la Fase 0:

- Verificar las IPs de enlaces internos: `191.168.0.138`, `191.168.0.222`, `191.168.50.33`, `191.168.8.201`, `191.168.0.65`. El rango `191.x` no es privado; probable typo de `192.168.x`. Confirmar con TI en Fase 4
- El logo (`public/img/company-logo.png`) es un JPEG renombrado, 200×189, sin transparencia. Conseguir el vectorial de marketing en Fase 1
- Las migraciones `2024_02_08_201339_create_documents_table` y `2024_02_08_201346_create_posts_table` nunca se corrieron. No migrar ese esquema; la Fase 4 define el suyo
- Los ~250 cumpleaños del HTML no traen año ni correo. El matching contra empleados será por nombre y va a fallar en homónimos. Presupuestar revisión manual en el plan 02-02
- Confirmar con RH si existe una hoja de cálculo de empleados más actualizada que el HTML antes de correr el importador en Fase 2
- **[Fase 0] Los respaldos creados viven en `C:` de una sola máquina.** Copiarlos a un destino externo (R9). Hasta entonces, un fallo de disco los pierde
- **[Fase 0] El servidor tiene 10 versiones de PHP instaladas** (5.6 a 8.3). Limpiar en el plan 01-01, y de paso mover el pool de FPM a 8.3 porque Laravel 13 lo exige
- **[Fase 0] Laragon `php-8.3.30` no tiene `php.ini`** y por eso no carga `curl` ni `openssl`. No apuntar el proyecto a esa instalación. Las que sirven: XAMPP 8.2.12 (la del PATH) y Laragon 8.4.24

### Blockers/Concerns

- 🔴 **BLOQUEO NUEVO (Fase 1): `sudo` pide contrasena en el servidor.** `raulb` esta en el grupo `sudo` pero el acceso SSH es solo por llave. Eso impide instalar `intl`, `gd`, `bcmath`, `node` y `supervisor`, cambiar el pool de FPM a PHP 8.3 (que Laravel 13 requiere), y configurar swap. Es la tarea 1.9 y bloquea el despliegue de la v2
- 🔴 **BLOQUEO NUEVO (Fase 1): no existe ningun nombre DNS para el servidor.** Sin subdominio no hay HTTPS (Lets Encrypt no emite para IP cruda) ni despliegue en staging. El pendiente P4 dejo de ser un detalle
- 🔴 **BLOQUEO ACTIVO: la Fase 0 no cierra sin acciones manuales de Raúl.** Quedan tres: HTTPS en la v1 (R1, bloqueada ademas por falta de DNS), usuario de base de datos con permiso minimo (R2, script listo en projects/intranet-v2/scripts/), y cerrar el puerto 3306 al internet (R3). **R4 (versionado de S3) ya esta hecho y verificado.** Detalle en `projects/intranet-v2/FASE0-INFORME.md`
- 🔴 **El usuario de BD de la aplicación es superusuario alcanzable desde internet.** `ALL ON *.*` con `GRANT OPTION`, incluidos `SUPER` y `FILE`, sobre `raulb@%`, con MySQL escuchando en `0.0.0.0:3306`. Una inyección SQL en la v1 no compromete la base: compromete el servidor
- 🟡 **Ruta de perdida de datos en S3: mitigada, no resuelta.** El versionado ya esta habilitado y verificado (2026-09-11), asi que una sobrescritura se puede revertir. Pero la causa sigue: `VideoController` usa `getClientOriginalName()` sin sufijo unico. Se corrige en la Fase 4 (DOC-10)
- 🟡 **1.9 GB de RAM sin swap** condiciona la arquitectura de infraestructura de la v2
- **Riesgo de calidad de datos en Fase 2.** El costo real no es el código, es limpiar los datos. Reservar tiempo de RH para validar el reporte de discrepancias
- **Dependencia externa en Fase 6.** El asistente AI necesita autorización de gasto. Diseñado para que su ausencia no bloquee nada más

### Buenas noticias verificadas

- **El `.env` nunca entró al historial de git.** Verificado con `git log --all -- .env .env.backup .env.backup2`: cero resultados. Los secretos nunca se publicaron en GitHub; la exposición era del sistema de archivos local. Eso convierte las rotaciones en higiene necesaria, no en respuesta a incidente
- **El bucket S3 no es público** (`IsPublic=false`)
- **Disco sobrado:** 92 GB libres de 96 GB
- **La limpieza del `.env` no rompió nada:** 10/10 pruebas de integración, 15/15 rutas HTTP, las tres páginas de S3 cuadran exactamente con el manifiesto, y el flujo de login funciona en caso positivo y negativo

## Deferred Items

- SSO con Microsoft 365 / Entra ID → v2 (AUTH-10, AUTH-11)
- Importador desde Tress Revolution → v2 (PPL-12), condicionado a que exista API
- Chino simplificado → v2 (UX-14)
- Aplicación instalable con soporte fuera de línea → v2 (UX-13)
- Integración con Monday y Helpdesk → v2 (COM-13)
- Onboarding con listas por puesto → v2 (COM-12)
- Tablero de uso y búsquedas sin resultado → v2 (OPS-10)
