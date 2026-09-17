# Ariel Hub — Intranet v2

## What This Is

Intranet corporativa de Ariel Premium Supply: el lugar único donde ~200 empleados en STL, West Coast, Taiwán, China, Alemania, Canadá y varios estados de USA encuentran a un compañero, un documento, una política, el calendario, una sala de juntas y el estatus de su trámite. Reemplaza la v1 (Laravel 8 con los datos hardcodeados en HTML) por una plataforma con base de datos, roles y un panel donde RH mantiene la información sin tocar código.

## Core Value

Que un empleado encuentre lo que busca en menos de 10 segundos, y que RH pueda mantenerlo actualizado sin pedirle nada a TI.

Si todo lo demás falla, esas dos cosas tienen que funcionar.

## Requirements

### Validated

<!-- Shipped y confirmado valioso en la v1. Se conserva en v2. -->

- ✓ Directorio de empleados con filtro por sede — v1 (pero hardcodeado; v2 lo mueve a BD)
- ✓ Reserva de salas de juntas con detección de choques — v1 (las 2 únicas tablas reales en BD)
- ✓ Documentos, boletines y galería servidos desde S3 + CloudFront — v1
- ✓ Catálogo de enlaces a las 20 apps internas — v1 (hardcodeado)
- ✓ Calendario de festivos MX/USA — v1 (hardcodeado, ya con fechas de 2024 vencidas)
- ✓ Lista de cumpleaños por mes — v1 (hardcodeada)

### Active

<!-- Alcance v2. Los IDs rastreables viven en .planning/REQUIREMENTS.md -->

- [ ] Autenticación real: usuarios propios, contraseñas hasheadas, roles, reset por correo
- [ ] Empleados, departamentos y sedes en base de datos, con panel de administración para RH
- [ ] Directorio con búsqueda instantánea, fotos y organigrama
- [ ] Cumpleaños y aniversarios generados automáticamente desde los datos de empleados
- [ ] Motor de festivos por reglas que calcula cualquier año, sin fechas hardcodeadas
- [ ] Salas de juntas rehechas, sin doble reserva y con confirmación por correo
- [ ] Documentos, boletines y galería administrables, con categorías y permisos
- [ ] Anuncios y noticias con editor, programación de publicación y notificaciones
- [ ] Muro de reconocimientos y encuestas rápidas
- [ ] Autoservicio: vacaciones, TI, mantenimiento y requisición de compra, con flujo de aprobación y estatus visible
- [ ] Búsqueda global ⌘K sobre personas, documentos, enlaces y políticas
- [ ] Asistente AI que responde citando el documento fuente, con proveedor configurable y apagado por defecto
- [ ] Bilingüe ES/EN en toda la interfaz
- [ ] Sistema de diseño Executive Light con modo oscuro y accesibilidad AA

### Out of Scope

- **SSO con Microsoft 365 / Entra ID** — se evaluó y se descartó para v2: requiere que TI registre una app en Azure y que AD tenga los campos de perfil poblados. Queda como candidato v3; la capa de auth se construye para poder añadirlo después sin migrar usuarios.
- **Sincronización con Tress Revolution (HRIS)** — descartada porque no hay API confirmada. La intranet es la fuente de verdad del directorio. Si Tress expone API después, entra como importador, no como rediseño.
- **Chino simplificado** — TW y CN aparecen en el directorio pero no está confirmado que sean usuarias de la intranet. La infraestructura de i18n lo soporta sin cambios de código.
- **App móvil nativa** — el diseño responsivo cubre el caso de uso. Reconsiderar solo si se mide tráfico móvil alto.
- **Modelo AI local en el servidor** — el EC2 no tiene GPU; la calidad sería mala y el mantenimiento alto.
- **Reemplazar Monday, el Helpdesk o Metabase** — la intranet enlaza e integra; no sustituye herramientas que ya funcionan.
- **Nómina, evaluaciones de desempeño y expediente laboral** — es territorio del HRIS y trae obligaciones de privacidad que no queremos en una app expuesta a internet.

## Context

**Estado real de la v1** (auditado el 2026-09-10 sobre el commit `14dc648`):

| Área | Hallazgo |
|---|---|
| Framework | Laravel 8.83, EOL desde enero 2023, corriendo sobre PHP 8.4 |
| CSS | `public/css/intranet.css` **no existe** — las 23 vistas lo enlazan y devuelve 404; todo el diseño vive en un bloque `<style>` duplicado en cada archivo |
| Layouts | `resources/layouts/header.blade.php` y `sidebar.blade.php` existen pero **ninguna vista los usa**. Cada página repite header, menú y footer a mano, y los menús ya están desincronizados (“Cumpleaños” vs “Cumpleañeros”, Calendario ausente en varias) |
| Base de datos | `arielhub` solo tiene 2 tablas: `meetingrooms` (3 filas) y `reservations` (2 filas). Las migraciones de `documents` y `posts` nunca se corrieron |
| Directorio | ~200 empleados hardcodeados en 2,304 líneas de `directory.blade.php` |
| Cumpleaños | ~250 nombres hardcodeados por mes en `birthdays.blade.php` |
| Festivos | Array PHP hardcodeado con fechas de 2024 en `calendar.blade.php:71` — ya muestra datos vencidos |
| Enlaces | 20 apps hardcodeadas; varias con IPs `191.168.x.x`, que no es rango privado y es probable typo de `192.168.x.x` |
| Autenticación | Una sola contraseña compartida (`AUTH_USER`/`AUTH_PASS` en .env) comparada en texto plano. Sin usuarios, sin roles, sin hash, sin rate limiting |
| S3 | Funciona, pero `VideoController` instancia `S3Client` a mano con `env()` en el constructor — se rompe con `config:cache` — en lugar de usar el disco `s3` de Laravel |
| Duplicación | 5 copias completas de `welcome.blade.php` (halloween, usdday, mxdday, hollidays, backup) usadas como forma de cambiar el tema de temporada |
| Bug de salas | La validación de choques no excluye reservas con `status = rechazado`, así que una reserva rechazada sigue bloqueando la sala |
| Logo | `company-logo.png` es en realidad un JPEG de 200×189 sin transparencia |

**Riesgo de seguridad heredado:** el `.env` (correctamente ignorado por git) contiene en comentarios la contraseña root del VPS, la contraseña de GitHub y credenciales de otras bases de datos en texto plano, además de llaves AWS de larga duración. La Fase 0 rota esos secretos y mueve la documentación de accesos a `asistente-ejecutivo-trabajo/env/accesos.md`, que ya existe para ese propósito y está en `.gitignore`.

**Precedente interno:** `qualion` y `pm-ariel` ya usan Laravel + Livewire + Tailwind. La v2 adopta el mismo stack para que una sola persona mantenga los tres.

**Marca:** rojo `#ED2228` extraído del logo, sobre blanco. El logo es un wordmark script, así que es la única voz decorativa del sistema; la interfaz usa una sans neutra.

## Constraints

- **Tech stack**: Laravel 12 + Livewire 3 + Tailwind 4 + MySQL 8 — mismo stack que qualion y pm-ariel, para que una sola persona pueda mantener los tres.
- **Equipo**: una persona (Raúl) desarrolla y opera. Nada que requiera equipo de plataforma ni guardia 24/7.
- **Compatibilidad**: la v1 debe seguir en línea sin interrupciones durante toda la construcción de la v2. Cero big-bang.
- **Infraestructura**: mismo EC2 (54.71.240.78), subdominio nuevo, expuesta a internet. Eso obliga a HTTPS, rate limiting, bloqueo por intentos y 2FA opcional para admins.
- **Datos**: la intranet es la fuente de verdad de empleados. No hay integración con HRIS.
- **Privacidad**: el directorio contiene datos de contacto de empleados reales. Solo se guarda mes y día de cumpleaños, nunca el año, y nada de PII sensible.
- **Presupuesto AI**: cero costo hasta autorización explícita. El asistente arranca con driver `null`.
- **Ritmo**: sin fecha dura, pero cada ola de 1 a 2 semanas debe dejar algo navegable que Raúl pueda mostrar.
- **Idioma**: documentación en español; código y nombres de base de datos en inglés con comentarios en español (convención de asistente-ejecutivo-trabajo).

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Rebuild v2 en paralelo en lugar de upgrade in-place de Laravel 8→12 | El código actual no tiene casi nada que valga la pena conservar: sin layouts, sin CSS, sin modelos, datos en HTML. Un upgrade arrastraría toda la deuda y el rediseño tardaría mucho más en verse. La v1 sigue viva mientras se construye | — Pending |
| Laravel 12 + Livewire 3 + Tailwind 4 | Mismo stack que qualion y pm-ariel. Server-rendered, un solo build, mantenible por una persona. Evita el costo de operar una API más un SPA | — Pending |
| Usuarios propios en lugar de SSO M365 | No bloquea el proyecto esperando a que TI registre la app en Azure. La capa de auth se construye para admitir un proveedor SSO después sin migrar usuarios | — Pending |
| La intranet es la fuente de verdad de empleados | No hay API confirmada de Tress Revolution. RH administra desde el panel, que es exactamente lo que pidió el negocio | — Pending |
| Bilingüe ES/EN desde el arranque | Hay sedes en USA, MX, TW, CN, DE y CA, y la v1 ya mezcla los dos idiomas de forma inconsistente. Retrofitear i18n sobre 305 vistas fue caro en qualion; no repetir el error | — Pending |
| Diseño Executive Light: rojo solo como acento | `#ED2228` a pantalla completa cansa en jornadas de 8 horas, y además no alcanza contraste AA como texto sobre blanco (~4.0:1). Se usa como fondo de botón con texto blanco, y `#C91B21` para texto rojo (~5.3:1) | — Pending |
| Mes y día de cumpleaños en lugar de fecha de nacimiento | Los datos actuales no traen año, la funcionalidad no lo necesita, y guardar menos PII en una app expuesta a internet es la decisión correcta | — Pending |
| Festivos por reglas, no por fechas | El array hardcodeado ya está vencido con fechas de 2024. Un motor de reglas (fijo, n-ésimo día de semana, corrimiento por observancia) calcula cualquier año sin mantenimiento | — Pending |
| Capa AI con drivers `claude`/`openai`/`null`, default `null` | Raúl pidió dejar la autenticación lista para cualquiera de los dos proveedores sin comprometer gasto. La búsqueda ⌘K funciona completa sin AI | — Pending |
| Documentación PM en asistente-ejecutivo-trabajo, ejecución GSD en este repo | Respeta el modelo de dos espacios de trabajo ya establecido: planeación de negocio en el vault, código y fases ejecutables en el repo | — Pending |

## Evolution

**Después de cada transición de fase:**
1. ¿Requisitos invalidados? → mover a Out of Scope con razón
2. ¿Requisitos validados? → mover a Validated con referencia de fase
3. ¿Nuevos requisitos? → agregar a Active
4. ¿Decisiones que registrar? → agregar a Key Decisions y a `asistente-ejecutivo-trabajo/decisions/log.md`
5. ¿"What This Is" sigue siendo exacto? → actualizar si cambió

**Después de cada hito:** revisión completa de todas las secciones, verificación del Core Value, auditoría de las razones de Out of Scope, y actualización de Context con el estado real (usuarios, feedback, métricas de uso).

---
*Last updated: 2026-09-10 después de la auditoría inicial de la v1 y el cierre de las 10 decisiones de alcance con Raúl*
