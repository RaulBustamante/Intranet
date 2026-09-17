# Requirements: Ariel Hub — Intranet v2

**Defined:** 2026-09-10
**Core Value:** Que un empleado encuentre lo que busca en menos de 10 segundos, y que RH pueda mantenerlo actualizado sin pedirle nada a TI.

Este archivo es la **fuente canónica de IDs** del proyecto. Los documentos de negocio en `asistente-ejecutivo-trabajo/projects/intranet-v2/` referencian estos mismos IDs — no existe una numeración paralela RF-001, a propósito, para que no haya dos listas que se desincronicen.

---

## v1 Requirements

### OPS — Blindaje, infraestructura y operación

- [ ] **OPS-01**: Todas las credenciales expuestas en texto plano en `.env` (AWS, BD, root del VPS, GitHub) quedan rotadas y las nuevas nunca aparecen en el repositorio
- [ ] **OPS-02**: La documentación de accesos vive únicamente en `asistente-ejecutivo-trabajo/env/accesos.md`, que está en `.gitignore`, y el `.env` del proyecto queda sin comentarios con secretos
- [ ] **OPS-03**: El usuario IAM de S3 tiene permisos mínimos, limitados al bucket `apsi-hrevent` y a los prefijos que la aplicación realmente usa
- [ ] **OPS-04**: Existe respaldo verificado de la base de datos y del bucket S3, tomado antes de cualquier cambio, y se puede restaurar en un entorno de prueba
- [ ] **OPS-05**: La v2 está desplegada en un subdominio propio con HTTPS válido y renovación automática, sin tocar la v1
- [ ] **OPS-06**: Respaldo automático diario de base de datos y archivos, con retención definida y una restauración probada al menos una vez
- [ ] **OPS-07**: Existe un runbook de despliegue y de reversión que un tercero pueda seguir sin contexto previo
- [ ] **OPS-08**: `APP_DEBUG=false` en producción y los errores se registran sin exponer traza al usuario
- [ ] **OPS-09**: Cola de trabajos y planificador corriendo de forma supervisada, para que correos y tareas programadas no dependan de que alguien ejecute algo a mano

<!-- OPS-11 a OPS-15 se agregaron el 2026-09-10 tras ejecutar la Fase 0.
     No estaban en el plan original: los descubrió el inventario del servidor.
     Detalle y evidencia en projects/intranet-v2/FASE0-INFORME.md -->

- [ ] **OPS-11**: La intranet sirve por HTTPS con certificado válido y renovación automática, y el tráfico HTTP redirige a HTTPS. Aplica a la v1 de inmediato, no solo a la v2 — hoy la v1 solo escucha en el puerto 80 y la contraseña compartida viaja en texto claro
- [ ] **OPS-12**: La base de datos no acepta conexiones desde internet abierto; el acceso queda restringido a los orígenes que realmente lo necesitan
- [ ] **OPS-13**: El usuario de base de datos que usa la aplicación tiene únicamente los privilegios de datos que necesita sobre una sola base. Hoy tiene `ALL ON *.*` con `GRANT OPTION`, incluidos `SUPER` y `FILE`, desde cualquier host
- [ ] **OPS-14**: El bucket de S3 tiene versionado habilitado, de modo que un borrado o una sobrescritura se pueden revertir
- [ ] **OPS-15**: El servidor tiene instalado lo que la v2 necesita (`intl`, `gd`, `bcmath`, `node`/`npm`, `supervisor`), tiene swap configurado, y no conserva versiones de PHP sin uso

### AUTH — Identidad, roles y sesión

- [ ] **AUTH-01**: Cada empleado tiene su propia cuenta con correo corporativo y contraseña hasheada; desaparece la contraseña compartida
- [ ] **AUTH-02**: El empleado puede restablecer su contraseña por correo con un enlace de un solo uso y vencimiento
- [ ] **AUTH-03**: El login limita intentos por IP y por cuenta, y bloquea temporalmente tras intentos fallidos repetidos
- [ ] **AUTH-04**: La sesión persiste entre recargas, expira por inactividad, y el usuario puede cerrar sesión en todos sus dispositivos
- [ ] **AUTH-05**: Existen los roles `employee`, `hr_editor`, `content_editor`, `approver` y `admin`, y cada uno solo ve y hace lo que le corresponde
- [ ] **AUTH-06**: Las cuentas con rol `admin` o `hr_editor` pueden activar segundo factor (TOTP)
- [ ] **AUTH-07**: Toda acción de administración queda registrada con quién, qué, cuándo y desde dónde, y el registro es consultable por `admin`
- [ ] **AUTH-08**: Dar de baja a un empleado en el panel de RH revoca su acceso inmediatamente, sin borrar su historial
- [ ] **AUTH-09**: La capa de autenticación admite añadir un proveedor SSO después sin migrar ni recrear usuarios
- [x] **AUTH-12**: Al ser una red interna, TODO el contenido es navegable sin sesión (directorio, calendario, boletines, documentos, enlaces, salas, solicitudes, kudos, búsqueda); la sesión solo se exige para CAPTURAR (enviar una solicitud, reservar una sala, dar un kudo, votar una encuesta) y para lo estrictamente personal (perfil, conexiones) y de administración. El candado de captura no bloquea la navegación: al intentar la acción sin sesión, redirige al login. *(Decisión de Raúl, 2026-09-11: "que en todas no se requiere pero… las que se puede meter solicitudes no puedan capturar nada a menos que se firmen… no importa, es interna en red privada")*

### PPL — Personas y directorio

- [ ] **PPL-01**: Los ~200 empleados del HTML actual están importados a base de datos, con reporte de las filas que requirieron intervención manual
- [ ] **PPL-02**: Los ~250 cumpleaños del HTML actual están importados y ligados a su empleado, con reporte de los que no lograron match automático
- [ ] **PPL-03**: Sedes y departamentos son catálogos administrables, no cadenas de texto repetidas
- [ ] **PPL-04**: El directorio busca por nombre, apellido, departamento, sede, extensión, teléfono y correo, y responde mientras se escribe
- [ ] **PPL-05**: El directorio se puede ver como tabla densa o como tarjetas con foto, y la preferencia se recuerda
- [ ] **PPL-06**: Cada persona tiene una ficha con foto, puesto, sede, departamento, extensión, correo, jefe y equipo directo
- [ ] **PPL-07**: Existe un organigrama navegable construido desde la relación de jefe directo
- [ ] **PPL-08**: El empleado puede editar un conjunto acotado de sus propios datos (foto, extensión, teléfono, apodo) sin poder tocar puesto, sede ni departamento
- [ ] **PPL-09**: El directorio se puede exportar a CSV o PDF por sede o por departamento
- [ ] **PPL-10**: Solo se almacena mes y día de cumpleaños, nunca el año

### HRADM — Panel de administración de RH

- [ ] **HRADM-01**: RH da de alta, edita y da de baja empleados desde una interfaz web, sin tocar código ni base de datos
- [ ] **HRADM-02**: RH administra los catálogos de sedes y departamentos, incluida la jerarquía de departamentos
- [ ] **HRADM-03**: RH sube y recorta la foto de cada empleado
- [ ] **HRADM-04**: RH puede cargar un CSV para altas y actualizaciones masivas, con vista previa de los cambios antes de aplicarlos
- [ ] **HRADM-05**: El panel valida datos antes de guardar: correo único y con formato, extensión sin duplicar dentro de la misma sede, campos obligatorios
- [ ] **HRADM-06**: El panel no es accesible ni descubrible para quien no tenga rol `hr_editor` o `admin`
- [ ] **HRADM-07**: Cada cambio en datos de empleados queda registrado con autor y valor anterior

### CAL — Calendario y festivos

- [ ] **CAL-01**: Los festivos se definen como reglas (fecha fija, n-ésimo día de la semana del mes, corrimiento por observancia) y el sistema calcula las fechas de cualquier año automáticamente
- [ ] **CAL-02**: Los festivos distinguen país (MX, USA o ambos) y el empleado ve por defecto los de su sede
- [ ] **CAL-03**: Los cumpleaños del mes aparecen en el calendario generados desde los datos de empleados, sin captura aparte
- [ ] **CAL-04**: Los aniversarios laborales aparecen en el calendario cuando existe fecha de ingreso
- [ ] **CAL-05**: RH y comunicación crean, editan y borran eventos de empresa con título, descripción, fecha, sede y público objetivo
- [ ] **CAL-06**: El calendario se ve por mes, por lista de próximos eventos, y filtrado por tipo y sede
- [ ] **CAL-07**: El calendario se puede suscribir desde Outlook o Google mediante una URL iCal con token por usuario
- [ ] **CAL-08**: Los nombres de festivos y eventos se muestran en el idioma activo

### ROOM — Salas de juntas

- [ ] **ROOM-01**: El empleado ve la disponibilidad real de cada sala en una vista de tiempo, no en un formulario a ciegas
- [ ] **ROOM-02**: Es imposible generar dos reservas que se solapen en la misma sala, garantizado también a nivel de base de datos y no solo en la aplicación
- [ ] **ROOM-03**: Las reservas canceladas o rechazadas dejan de bloquear el horario (corrige el defecto de la v1)
- [ ] **ROOM-04**: La reserva queda ligada al empleado autenticado; ya no se captura el nombre a mano
- [ ] **ROOM-05**: El empleado ve, edita y cancela sus propias reservas, y solo las suyas
- [ ] **ROOM-06**: Se pueden crear reservas recurrentes y cancelar una ocurrencia o toda la serie
- [ ] **ROOM-07**: Quien reserva recibe confirmación por correo con un archivo de calendario adjunto
- [ ] **ROOM-08**: Las salas son un catálogo administrable con sede, capacidad, equipo disponible y color

### DOC — Documentos, boletines y galería

- [ ] **DOC-01**: Toda la interacción con S3 pasa por el disco `s3` de Laravel; se elimina la instanciación manual de `S3Client` con `env()`
- [ ] **DOC-02**: Los documentos se organizan en categorías administrables con permisos por rol y por sede
- [ ] **DOC-03**: La subida es por arrastrar y soltar, con barra de progreso, validación de tipo y tamaño, y mensaje de error entendible
- [ ] **DOC-04**: Cada documento conserva versiones, y se puede ver y descargar una versión anterior
- [ ] **DOC-05**: Los boletines se listan por año y mes con portada, y el más reciente es evidente
- [ ] **DOC-06**: La galería se organiza en álbumes por evento con fecha, portada, miniaturas y visor a pantalla completa
- [ ] **DOC-07**: Los videos se sirven por CloudFront y se reproducen sin descarga directa desde la interfaz
- [ ] **DOC-08**: Si S3 falla, la página se renderiza igual con un aviso claro y el error queda en el log (comportamiento que ya se corrigió en la v1 y que no se debe perder)
- [ ] **DOC-09**: Los archivos privados se sirven por URL firmada con vencimiento, no por URL pública
- [ ] **DOC-10**: Las claves de S3 se generan únicas por archivo. Subir un archivo cuyo nombre coincide con uno existente no sobrescribe el anterior — hoy `VideoController` usa `getClientOriginalName()` sin sufijo y sobrescribe de forma silenciosa y permanente

### COM — Comunicación interna

- [ ] **COM-01**: RH y comunicación publican anuncios con título, resumen, cuerpo con formato, portada y adjuntos
- [ ] **COM-02**: Un anuncio se puede programar para publicarse en una fecha y expirar en otra
- [ ] **COM-03**: Un anuncio se puede fijar arriba y dirigirse a toda la empresa o a sedes o departamentos específicos
- [ ] **COM-04**: El empleado recibe notificaciones dentro de la intranet, con contador de no leídas
- [ ] **COM-05**: Existe un resumen semanal por correo con lo nuevo relevante, y el empleado puede darse de baja de él
- [ ] **COM-06**: Existe un banner de aviso urgente que un `admin` activa y desactiva sin desplegar código
- [ ] **COM-07**: El tema visual de temporada se cambia desde configuración; se eliminan las 5 copias duplicadas de la página de inicio
- [ ] **COM-08**: El empleado puede dar un reconocimiento público a un compañero, con mensaje y valor asociado, y existe un muro con los recientes
- [ ] **COM-09**: Los reconocimientos pasan por moderación configurable antes de publicarse
- [ ] **COM-10**: RH lanza encuestas de una o varias preguntas, ve resultados agregados, y el empleado vota una sola vez
- [ ] **COM-11**: La página de inicio es personalizada: saludo con el nombre, cumpleaños de hoy, próximos eventos, mis reservas, mis solicitudes abiertas y lo último publicado

### REQ — Autoservicio del empleado

- [ ] **REQ-01**: Existen cuatro tipos de solicitud: vacaciones o permiso, soporte TI, mantenimiento y requisición de compra
- [ ] **REQ-02**: Cada tipo define sus propios campos sin necesidad de programar una pantalla nueva por tipo
- [ ] **REQ-03**: El empleado envía una solicitud y ve su estatus y su historial en todo momento
- [ ] **REQ-04**: Cada tipo tiene un flujo de aprobación de uno o varios pasos, con aprobador por rol o por jefe directo
- [ ] **REQ-05**: El aprobador tiene una bandeja con lo que le toca resolver, y puede aprobar o rechazar con comentario obligatorio al rechazar
- [ ] **REQ-06**: Solicitante y aprobador reciben notificación en cada cambio de estatus
- [ ] **REQ-07**: Se pueden adjuntar archivos a una solicitud y comentar dentro de ella
- [ ] **REQ-08**: Cada tipo tiene un tiempo de respuesta comprometido y las solicitudes vencidas se destacan
- [ ] **REQ-09**: RH y admin ven un tablero con solicitudes por tipo, estatus, área y tiempo de resolución
- [ ] **REQ-10**: Un empleado solo puede ver sus propias solicitudes; el aprobador solo las de su ámbito

### SRCH — Búsqueda y asistente

- [ ] **SRCH-01**: Un atajo de teclado abre una búsqueda global sobre personas, documentos, boletines, anuncios, enlaces, eventos y salas
- [ ] **SRCH-02**: La búsqueda encuentra texto dentro del contenido de los PDF, no solo en el nombre del archivo
- [ ] **SRCH-03**: La búsqueda tolera acentos y errores de dedo, y funciona en español y en inglés
- [ ] **SRCH-04**: Los resultados respetan permisos: nadie ve en la búsqueda algo que no podría abrir
- [ ] **SRCH-05**: La búsqueda también ejecuta acciones, no solo navega: reservar sala, subir documento, ir a una ficha
- [ ] **SRCH-06**: El proveedor del asistente AI se configura por variable de entorno y admite Claude, OpenAI o ninguno, con `ninguno` como valor por defecto
- [ ] **SRCH-07**: Con el asistente activo, una pregunta en lenguaje natural sobre políticas de RH o ISO se responde citando el documento y la sección de donde salió
- [ ] **SRCH-08**: El asistente nunca inventa: si no encuentra respaldo en los documentos indexados, lo dice
- [ ] **SRCH-09**: El asistente respeta permisos y jamás cita un documento que el usuario no tenga derecho a ver
- [ ] **SRCH-10**: Con el proveedor apagado, toda la búsqueda global sigue funcionando sin degradación

### UX — Diseño, idioma y accesibilidad

- [ ] **UX-01**: Existe un único layout y una única definición de navegación; se elimina el header, menú y footer repetidos en cada vista
- [ ] **UX-02**: Existe un sistema de tokens de diseño (color, tipografía, espaciado, radio, sombra) y ninguna vista define color o espaciado a mano
- [ ] **UX-03**: El rojo de marca se usa solo como acento en acciones y estados; nunca como fondo de páginas o encabezados
- [ ] **UX-04**: Todo texto cumple contraste AA, incluido el rojo, que usa `#C91B21` para texto y `#ED2228` solo como fondo de botón con texto blanco
- [ ] **UX-05**: Existe modo claro y modo oscuro, respetando la preferencia del sistema y permitiendo elección explícita que se recuerda
- [ ] **UX-06**: Toda la interfaz está en archivos de traducción ES/EN, con selector visible y detección inicial por navegador; no hay texto fijo en las vistas
- [ ] **UX-07**: El contenido administrable con dos idiomas (anuncios, categorías, enlaces, eventos) admite versión en español y en inglés, y cae al idioma disponible si falta uno
- [ ] **UX-08**: Toda la aplicación funciona en teléfono, incluido el directorio y el calendario
- [ ] **UX-09**: Se puede navegar y operar solo con teclado, y el foco siempre es visible
- [ ] **UX-10**: Cada lista tiene estado vacío, estado de carga y estado de error diseñados; ninguna pantalla se queda en blanco
- [ ] **UX-11**: El logo existe en versión vectorial con fondo transparente, en claro y en oscuro
- [ ] **UX-12**: Las páginas principales cargan en menos de 1.5 s en la red de oficina, y el directorio completo pagina o virtualiza en lugar de enviar 200 filas de golpe

---

## v2 Requirements

Diferido. Rastreado pero fuera del roadmap actual.

### Identidad
- **AUTH-10**: SSO con Microsoft 365 / Entra ID, con roles mapeados desde grupos de AD
- **AUTH-11**: Aprovisionamiento y baja automática de cuentas desde el directorio corporativo

### Personas
- **PPL-11**: Organigrama editable arrastrando personas entre jefes
- **PPL-12**: Importador desde Tress Revolution, si expone API
- **PPL-13**: Indicador de presencia y horario local por sede

### Comunicación
- **COM-12**: Onboarding con listas de tareas por puesto y seguimiento del primer mes
- **COM-13**: Integración con Monday y con el Helpdesk para reflejar estatus real dentro de la intranet

### Plataforma
- **UX-13**: Instalable como aplicación con soporte fuera de línea para directorio y documentos
- **UX-14**: Tercer idioma (chino simplificado) para TW y CN
- **OPS-10**: Tablero de uso: qué secciones se visitan, qué se busca sin encontrar resultado

---

## Out of Scope

| Feature | Reason |
|---------|--------|
| SSO M365 en v1 | Depende de que TI registre una app en Azure y pueble AD. Bloquearía el arranque; la capa de auth se diseña para admitirlo después |
| Sincronización con HRIS Tress | Sin API confirmada. La intranet queda como fuente de verdad, que es lo que pidió el negocio |
| Chino simplificado | Sin confirmar que TW y CN sean usuarios reales de la intranet. La i18n lo soporta sin cambios de código |
| App móvil nativa | El diseño responsivo cubre el caso. Reconsiderar solo con datos de tráfico móvil |
| Modelo AI local | El EC2 no tiene GPU: mala calidad y alto mantenimiento |
| Reemplazar Monday, Helpdesk o Metabase | La intranet enlaza e integra; no sustituye herramientas que funcionan |
| Nómina, desempeño y expediente laboral | Territorio del HRIS, con obligaciones de privacidad que no queremos en una app expuesta a internet |
| Chat o mensajería interna | Ya existe Teams/Meet/Zoom. Construirlo sería duplicar y competir con lo que la gente ya usa |
| Editor de flujos de aprobación configurable por el usuario | Los cuatro flujos de v1 se definen en código con configuración por tipo. Un constructor visual es meses de trabajo para un problema que aún no existe |

---

## Traceability

| Requirement | Phase | Status |
|-------------|-------|--------|
| OPS-02 | Phase 0 | ✅ Done 2026-09-10 |
| OPS-04 | Phase 0 | ✅ Done 2026-09-10 (restauración verificada por conteo y checksum) |
| OPS-01, OPS-03 | Phase 0 | Pending — requiere consola IAM (R5, R6) |
| OPS-14 | Phase 0 | ✅ Done 2026-09-10 (versionado habilitado y verificado recuperando una versión anterior) |
| OPS-13 | Phase 0 | Pending — script listo y probado por diseño: `projects/intranet-v2/scripts/r2-usuario-bd-minimo.sh` (R2) |
| OPS-12 | Phase 0 | Pending — requiere consola AWS (R3). Verificado que cerrarlo no rompe el sitio: el servidor usa `DB_HOST=localhost` |
| OPS-11 | Phase 0 | **Blocked** — no existe nombre DNS para el servidor (Let's Encrypt no emite para IP cruda) y `sudo` pide contraseña (R1) |
| OPS-05, OPS-08 | Phase 1 | Pending |
| OPS-15 | Phase 1 | Pending |
| OPS-09 | Phase 4 | Pending |
| DOC-10 | Phase 4 | Pending |
| OPS-06, OPS-07 | Phase 7 | Pending |
| AUTH-01 … AUTH-09 | Phase 2 | Pending |
| PPL-01…PPL-08,PPL-10 | Phase 2 | ✅ Done 2026-09-11 (PPL-09 exportar pendiente) |
| HRADM-01,03,04,05,06 | Phase 2 | ✅ Done 2026-09-11 (catalogos/auditoria pendientes) |
| CAL-01 … CAL-08 | Phase 3 | ✅ Done 2026-09-11 (CAL-07 iCal incluido) |
| ROOM-01 … ROOM-08 | Phase 3 | ✅ Done 2026-09-11 (ROOM-06 recurrencia diferida) |
| DOC-01, DOC-02, DOC-05, DOC-09 | Phase 4 | ✅ Done 2026-09-11 (subida/versiones/galería pendientes) |
| COM-02, COM-03, COM-06, COM-07, COM-08, COM-09, COM-11 | Phase 4 | ✅ Done 2026-09-11 (notif/encuestas/resumen pendientes) |
| REQ-01…REQ-05, REQ-08, REQ-10 | Phase 5 | ✅ Done 2026-09-11 (adjuntos/tablero/notif pendientes) |
| SRCH-01,03,04,05,06,10 | Phase 6 | ✅ Done 2026-09-11 (SRCH-02 PDF y 07-09 AI real pendientes) |
| UX-01 … UX-05, UX-06, UX-10, UX-11 | Phase 1 | Pending |
| UX-07 | Phase 4 | Pending |
| UX-08, UX-09, UX-12 | Phase 7 | Pending |

**Coverage:**
- Requisitos v1: **109 total** (OPS 14, AUTH 9, PPL 10, HRADM 7, CAL 8, ROOM 8, DOC 10, COM 11, REQ 10, SRCH 10, UX 12)
- Mapeados a fases: 109
- Sin mapear: 0
- Diferidos a v2: 10
- **Completados: 3** (OPS-02, OPS-04, OPS-14 — Fase 0, 2026-09-10)
- **Bloqueado: 1** (OPS-11 HTTPS — falta un nombre DNS para el servidor y la contraseña de `sudo`)

Cambio respecto a la línea base: el conteo pasó de 103 a 109. Los 6 nuevos (OPS-11 a OPS-15 y DOC-10) los descubrió la ejecución de la Fase 0, no la planeación. Ver `projects/intranet-v2/FASE0-INFORME.md`.

---
*Requirements defined: 2026-09-10*
*Last updated: 2026-09-10 — versión inicial*
