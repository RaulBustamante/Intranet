# Roadmap: Ariel Hub — Intranet v2

## Overview

Ocho fases que van de blindar lo que ya existe a apagar la v1. La lógica del orden es una sola: **cada fase deja algo navegable que Raúl pueda mostrar**, y ninguna fase depende de algo que no esté terminado. La Fase 0 no se ve pero es obligatoria porque hay credenciales expuestas. La Fase 1 no tiene datos pero es donde el glow up se vuelve visible. La Fase 2 es el corazón del proyecto y lo que el negocio pidió textualmente: datos de contacto desde base de datos, más un panel donde RH los actualiza. De ahí en adelante cada fase agrega un módulo completo y cerrado, y la Fase 7 hace el corte. La v1 sigue en línea desde el minuto cero hasta el corte.

## Phases

**Phase Numbering:**
- Integer phases (1, 2, 3): Planned milestone work
- Decimal phases (2.1, 2.2): Urgent insertions (marked with INSERTED)

- [~] **Phase 0: Blindaje y limpieza** - Rotar los secretos expuestos, respaldar, y verificar qué aguanta el servidor — **PARCIAL 2026-09-10**: limpieza, respaldo verificado e inventario hechos; rotaciones y HTTPS pendientes de Raúl
- [ ] **Phase 1: Cimientos v2** - Laravel 12 + sistema de diseño Executive Light + i18n + staging con HTTPS
- [ ] **Phase 2: Personas** - Empleados en base de datos, directorio nuevo, organigrama, autenticación real y panel de RH
- [ ] **Phase 3: Calendario y Salas** - Motor de festivos por reglas y reserva de salas sin doble booking
- [ ] **Phase 4: Contenido y Comunicación** - Documentos, boletines, galería, anuncios, notificaciones, kudos y encuestas
- [ ] **Phase 5: Autoservicio** - Cuatro tipos de solicitud con flujo de aprobación y estatus visible
- [ ] **Phase 6: Búsqueda y Asistente** - Búsqueda global ⌘K, texto dentro de PDFs y capa AI apagada por defecto
- [ ] **Phase 7: Corte a producción** - Auditorías, UAT, migración final, DNS, respaldos y runbook

## Phase Details

### Phase 0: Blindaje y limpieza — EJECUTADA PARCIALMENTE 2026-09-10
**Goal**: Que no haya credenciales vivas expuestas en texto plano y que exista un respaldo restaurable antes de tocar nada. Además, saber con certeza qué versiones corre el servidor.
**Depends on**: Nothing (first phase)
**Requirements**: OPS-01, OPS-02, OPS-03, OPS-04, OPS-11, OPS-12, OPS-13, OPS-14
**Informe con evidencia**: `../asistente-ejecutivo-trabajo/projects/intranet-v2/FASE0-INFORME.md`

**Success Criteria** (what must be TRUE):
  1. ⏳ Las llaves AWS, el acceso a la base de datos, la contraseña root del VPS y la contraseña de GitHub que estaban en texto plano en `.env` han sido rotadas, y las viejas ya no funcionan — *pendiente: requiere consola. Nota: se verificó que el `.env` **nunca entró al historial de git**, así que nunca se publicaron*
  2. ✅ El `.env` del proyecto no contiene ningún comentario con secretos; los accesos quedan documentados solo en `asistente-ejecutivo-trabajo/env/accesos.md` — *hecho: 3,487 → 2,120 bytes, 46 claves con equivalencia exacta verificada*
  3. ⏳ El usuario IAM de S3 solo puede leer y escribir en los prefijos que la aplicación usa dentro de `apsi-hrevent`, y nada más — *pendiente. Verificado que hoy tiene `ListAllMyBuckets` y ve los 9 buckets de la cuenta*
  4. ✅ Existe un respaldo de la base de datos `arielhub` y del bucket S3, y se restauró con éxito en un entorno de prueba — *hecho: restauración comparada por conteo de filas y por `CHECKSUM TABLE`, ambos idénticos. Manifiesto de los 39 objetos de S3 con MD5*
  5. ✅ Queda documentado qué versiones de PHP, MySQL y Apache corre el servidor, cuánto espacio libre tiene, y si aguanta la v2 o hay que mover algo — *hecho: Ubuntu 24.04, Apache 2.4.58, MySQL 8.0.46, **PHP 8.1-FPM** (no 8.4), 92 GB libres, **1.9 GB RAM sin swap***
  6. ⏳ La intranet v1 sirve por HTTPS y el HTTP redirige — *NUEVO: se descubrió que la v1 no tiene HTTPS en absoluto, solo puerto 80*
  7. ⏳ La base de datos no acepta conexiones desde internet abierto — *NUEVO: hoy escucha en `0.0.0.0:3306`, comprobado conectándose desde fuera*
  8. ⏳ El usuario de base de datos de la aplicación no es superusuario — *NUEVO: hoy tiene `ALL ON *.*` con `GRANT OPTION`, incluidos `SUPER` y `FILE`, desde cualquier host*
  9. ⏳ El bucket S3 tiene versionado habilitado — *NUEVO: hoy está deshabilitado, y la v1 sobrescribe por nombre de archivo sin recuperación*
  10. ✅ La limpieza no rompió el sitio — *hecho: 10/10 pruebas de integración, 15/15 rutas HTTP, S3 cuadra con el manifiesto, flujo de login verificado en caso positivo y negativo*

**Plans**: 3 plans

Plans:
- [x] 00-01: Limpieza del `.env`, archivado de los `.env.backup*`, y verificación de que nunca hubo secretos en git
- [x] 00-02: Respaldo verificado de base de datos y manifiesto de S3, más inventario de servidor y de PHP local
- [ ] 00-03: Rotaciones y endurecimiento — HTTPS, usuario de BD con permiso mínimo, cierre del 3306, versionado de S3, política IAM (R1–R10 del informe). **Requiere ejecución de Raúl en consola**

### Phase 1: Cimientos v2
**Goal**: Un esqueleto navegable con el diseño nuevo, desplegado en staging con HTTPS, en dos idiomas y con modo oscuro. Sin datos reales todavía, pero ya se ve y se siente como el producto final.
**Depends on**: Phase 0
**Requirements**: OPS-05, OPS-08, OPS-15, UX-01, UX-02, UX-03, UX-04, UX-05, UX-06, UX-10, UX-11
**Success Criteria** (what must be TRUE):
  1. La aplicación Laravel 12 con Livewire 3 y Tailwind 4 arranca en local y en staging, y la v1 sigue funcionando sin cambios
  2. Existe un único layout: cambiar un elemento de la navegación se refleja en todas las páginas a la vez
  3. Los tokens de color, tipografía, espaciado y radio están definidos en un solo lugar, y los componentes base (botón, tarjeta, insignia, tabla, avatar, estado vacío, encabezado de página) los consumen
  4. El modo oscuro funciona en toda la aplicación, respeta la preferencia del sistema y recuerda la elección explícita
  5. Cambiar de español a inglés en el selector cambia toda la interfaz; ninguna cadena visible está escrita dentro de una vista
  6. Todo texto de la interfaz pasa contraste AA, verificado con herramienta, incluido el rojo de marca en ambos modos
  7. Existe el logo en vectorial con fondo transparente, en variante clara y oscura
  8. `hub.arielpremium.com` (o el subdominio elegido) sirve la v2 por HTTPS con certificado válido y `APP_DEBUG=false`
  9. El servidor tiene `intl`, `gd`, `bcmath`, `node`/`npm` y `supervisor` instalados, tiene swap, y ya no conserva las 10 versiones de PHP sin uso (OPS-15)
  10. Está tomada y documentada la decisión de Redis vs. base de datos para cache, sesión y cola, con medición del consumo real de RAM

**Plans**: 5 plans

Plans:
- [ ] 01-01: Preparación del servidor: módulos PHP faltantes, node, supervisor, swap, limpieza de versiones de PHP, y medición de RAM para decidir Redis vs. base de datos
- [ ] 01-02: Instalación de Laravel 12, Livewire 3, Tailwind 4, Vite, paquetes base, y estructura de carpetas por dominio
- [ ] 01-03: Sistema de diseño: tokens, modo oscuro, tipografía, y la biblioteca de componentes Blade
- [ ] 01-04: Layout único, navegación desde configuración, i18n ES/EN, selector de idioma y de tema
- [ ] 01-05: Despliegue en staging con HTTPS, y logo vectorial

### Phase 2: Personas
**Goal**: Lo que el negocio pidió con nombre y apellido: los datos de contacto salen de la base de datos, y RH tiene una página propia para mantenerlos. Incluye la autenticación real porque el panel de RH no puede existir sin roles.
**Depends on**: Phase 1
**Requirements**: AUTH-01, AUTH-02, AUTH-03, AUTH-04, AUTH-05, AUTH-06, AUTH-07, AUTH-08, AUTH-09, PPL-01, PPL-02, PPL-03, PPL-04, PPL-05, PPL-06, PPL-07, PPL-08, PPL-09, PPL-10, HRADM-01, HRADM-02, HRADM-03, HRADM-04, HRADM-05, HRADM-06, HRADM-07
**Success Criteria** (what must be TRUE):
  1. Los ~200 empleados y los ~250 cumpleaños del HTML de la v1 están en base de datos, y existe un reporte de las filas que necesitaron intervención manual
  2. Un empleado entra con su correo corporativo y su propia contraseña; la contraseña compartida ya no existe en ninguna parte
  3. Un empleado que olvidó su contraseña la recupera solo, por correo, sin pedirle nada a TI
  4. Tras varios intentos fallidos el login bloquea temporalmente, y el bloqueo se puede comprobar
  5. Buscar "Jennifer" en el directorio devuelve resultados mientras se escribe, y filtrar por sede WC muestra solo esa sede
  6. La ficha de una persona muestra su foto, puesto, sede, extensión, correo, su jefe y su equipo directo, y desde ella se navega el organigrama
  7. Un usuario con rol `hr_editor` da de alta un empleado nuevo, le sube foto, lo edita y lo da de baja, todo desde la interfaz, y el cambio aparece de inmediato en el directorio y en cumpleaños
  8. Un usuario con rol `employee` no encuentra ni puede abrir el panel de RH, ni adivinando la URL
  9. Dar de baja a un empleado le revoca el acceso al instante y conserva su historial
  10. Cada cambio en datos de empleados queda en el registro de auditoría, con autor y valor anterior
  11. En la base de datos no existe año de nacimiento de nadie, solo mes y día
**Plans**: 6 plans

Plans:
- [ ] 02-01: Esquema de personas: sedes, departamentos, empleados, usuarios, roles y permisos, con índices y llaves foráneas
- [ ] 02-02: Importadores idempotentes desde el HTML de la v1 (directorio y cumpleaños) con reporte de discrepancias
- [ ] 02-03: Autenticación: registro por invitación, login con rate limiting, recuperación por correo, 2FA opcional, sesiones y auditoría
- [ ] 02-04: Directorio: búsqueda instantánea, filtros, vista tabla y tarjetas, exportación
- [ ] 02-05: Ficha de persona, organigrama y autoedición acotada del propio perfil
- [ ] 02-06: Panel de RH: CRUD de empleados, catálogos, fotos, carga masiva por CSV con vista previa

### Phase 3: Calendario y Salas
**Goal**: Un calendario que nunca vuelve a mostrar fechas vencidas porque calcula los festivos por regla, y una reserva de salas donde es imposible chocar dos juntas.
**Depends on**: Phase 2
**Requirements**: CAL-01, CAL-02, CAL-03, CAL-04, CAL-05, CAL-06, CAL-07, CAL-08, ROOM-01, ROOM-02, ROOM-03, ROOM-04, ROOM-05, ROOM-06, ROOM-07, ROOM-08
**Success Criteria** (what must be TRUE):
  1. Cambiar el año en el calendario a 2027, 2028 o 2030 muestra los festivos correctos de MX y USA sin que nadie haya capturado esas fechas
  2. Los festivos que caen en fin de semana y se observan otro día aparecen en el día observado
  3. Los cumpleaños del mes aparecen en el calendario sin captura adicional, tomados de los datos de empleados
  4. Un empleado de la sede WC ve por defecto los festivos que le aplican, y puede ver los de la otra sede si quiere
  5. Intentar reservar una sala en un horario ocupado es imposible desde la interfaz, y también lo es forzándolo con dos peticiones simultáneas
  6. Una reserva cancelada o rechazada libera el horario de inmediato
  7. La reserva se asocia al empleado autenticado sin capturar el nombre a mano, y cada quien ve y cancela solo las suyas
  8. Quien reserva recibe correo de confirmación con archivo de calendario adjunto
  9. Una reserva recurrente se crea de una vez, y se puede cancelar una sola ocurrencia o toda la serie
  10. El calendario se suscribe desde Outlook con una URL iCal y se mantiene actualizado
**Plans**: 4 plans

Plans:
- [ ] 03-01: Motor de festivos por reglas (fijo, n-ésimo día de semana, corrimiento por observancia) con catálogo MX/USA y pruebas por año
- [ ] 03-02: Eventos de empresa, inyección automática de cumpleaños y aniversarios, vistas de mes y de próximos, filtros y feed iCal
- [ ] 03-03: Esquema de salas y reservas con restricción de solapamiento a nivel de base de datos, y corrección del defecto de estatus rechazado
- [ ] 03-04: Interfaz de disponibilidad y reserva, mis reservas, recurrencia y correo de confirmación

### Phase 4: Contenido y Comunicación
**Goal**: Que la intranet se sienta viva: contenido administrable desde la interfaz, anuncios que llegan, y las 5 copias duplicadas de la página de inicio muertas para siempre.
**Depends on**: Phase 2
**Requirements**: OPS-09, DOC-01, DOC-02, DOC-03, DOC-04, DOC-05, DOC-06, DOC-07, DOC-08, DOC-09, COM-01, COM-02, COM-03, COM-04, COM-05, COM-06, COM-07, COM-08, COM-09, COM-10, COM-11, UX-07
**Success Criteria** (what must be TRUE):
  1. Ninguna parte del código instancia `S3Client` a mano; todo pasa por el disco `s3` de Laravel y sigue funcionando con la configuración en caché
  2. Un editor sube un documento arrastrándolo, ve el progreso, lo categoriza, y aparece publicado con los permisos correctos
  3. Subir una versión nueva de un documento conserva la anterior, y se puede descargar la anterior
  4. Un documento privado solo se abre por URL firmada con vencimiento; copiar la URL y abrirla al día siguiente falla
  5. Si S3 se cae, las páginas de documentos, boletines y galería siguen renderizando con aviso claro, y el error queda en el log
  6. Un anuncio programado para el viernes aparece el viernes solo, sin que nadie lo publique a mano
  7. Un anuncio dirigido a la sede WC no lo ve alguien de STL
  8. El empleado ve un contador de notificaciones no leídas y recibe el resumen semanal por correo, del que se puede dar de baja
  9. Un admin activa el banner de aviso urgente y cambia el tema de temporada desde configuración, sin desplegar código
  10. Los archivos `welcome.hollidays`, `welcome.usdday`, `welcomehalloween`, `welcomemxdday` y `welcome_backup` ya no existen en el repositorio
  11. Un empleado da un reconocimiento a un compañero y aparece en el muro tras la moderación configurada
  12. RH lanza una encuesta, el empleado vota una sola vez, y RH ve el resultado agregado
  13. La página de inicio saluda al empleado por su nombre y le muestra cumpleaños de hoy, próximos eventos, sus reservas, sus solicitudes abiertas y lo último publicado
  14. La cola de trabajos y el planificador corren supervisados: los correos salen sin que nadie ejecute nada
**Plans**: 6 plans

Plans:
- [ ] 04-01: Migración del acceso a S3 al disco de Laravel, URLs firmadas, cola y planificador supervisados
- [ ] 04-02: Documentos con categorías, permisos, versiones y subida por arrastrar y soltar
- [ ] 04-03: Boletines por año y mes, y galería por álbumes con miniaturas y visor
- [ ] 04-04: Anuncios con editor, programación, fijado, público objetivo y contenido bilingüe
- [ ] 04-05: Notificaciones dentro de la aplicación, resumen semanal por correo, banner de avisos y tema de temporada desde configuración
- [ ] 04-06: Reconocimientos con moderación, encuestas, y página de inicio personalizada

### Phase 5: Autoservicio
**Goal**: Que el empleado por fin sepa en qué va su trámite. Hoy son enlaces a WorkForms y Monday donde el solicitante nunca vuelve a saber nada.
**Depends on**: Phase 4
**Requirements**: REQ-01, REQ-02, REQ-03, REQ-04, REQ-05, REQ-06, REQ-07, REQ-08, REQ-09, REQ-10
**Success Criteria** (what must be TRUE):
  1. Un empleado envía una solicitud de vacaciones y ve su estatus en su página de inicio y en su historial
  2. Los cuatro tipos (vacaciones, TI, mantenimiento, requisición de compra) funcionan con campos distintos sin que exista una pantalla programada por tipo
  3. El aprobador que le toca ve la solicitud en su bandeja y recibe notificación
  4. Rechazar exige comentario; aprobar avanza al paso siguiente o cierra la solicitud
  5. Solicitante y aprobador reciben notificación en cada cambio de estatus
  6. Se puede adjuntar un archivo y comentar dentro de la solicitud
  7. Una solicitud que pasa su tiempo comprometido se destaca visualmente en la bandeja
  8. RH y admin ven un tablero con volumen por tipo, estatus, área y tiempo de resolución
  9. Un empleado no puede ver la solicitud de otro, ni con la URL directa
**Plans**: 4 plans

Plans:
- [ ] 05-01: Esquema de solicitudes: tipos con definición de campos, solicitudes, pasos de aprobación, comentarios y adjuntos
- [ ] 05-02: Formularios generados desde la definición del tipo, con validación
- [ ] 05-03: Motor de aprobación, bandeja del aprobador, notificaciones y tiempos comprometidos
- [ ] 05-04: Historial del empleado, tablero de RH y políticas de acceso

### Phase 6: Búsqueda y Asistente
**Goal**: Que ⌘K encuentre cualquier cosa de la empresa, y dejar la capa AI conectada y lista para prender el día que autoricen el gasto.
**Depends on**: Phase 5
**Requirements**: SRCH-01, SRCH-02, SRCH-03, SRCH-04, SRCH-05, SRCH-06, SRCH-07, SRCH-08, SRCH-09, SRCH-10
**Success Criteria** (what must be TRUE):
  1. Un atajo de teclado abre la búsqueda desde cualquier página y encuentra personas, documentos, boletines, anuncios, enlaces, eventos y salas
  2. Buscar una frase que solo aparece dentro de un PDF devuelve ese PDF
  3. Buscar "Nuno" encuentra a "Nuño", y buscar "vacacione" encuentra "vacaciones"
  4. Un empleado nunca ve en resultados un documento que no tendría derecho a abrir
  5. Desde la búsqueda se puede ejecutar una acción, no solo navegar
  6. Con `AI_PROVIDER=null` toda la búsqueda funciona igual, sin errores ni secciones rotas
  7. Cambiando `AI_PROVIDER` a `claude` o a `openai` y poniendo la llave, el asistente responde sin ningún otro cambio de código
  8. Con el asistente activo, preguntar por una política de RH devuelve la respuesta citando el documento y la sección
  9. Preguntar algo que no está en ningún documento indexado obtiene un "no encontré respaldo para eso", no una respuesta inventada
  10. El asistente nunca cita un documento que el usuario no puede ver
**Plans**: 4 plans

Plans:
- [ ] 06-01: Índice unificado de búsqueda con filtrado por permisos, tolerancia a acentos y errores de dedo
- [ ] 06-02: Extracción de texto de PDFs al índice, como trabajo en cola
- [ ] 06-03: Paleta de comandos ⌘K con navegación y acciones
- [ ] 06-04: Capa AI con drivers `claude`, `openai` y `null`, recuperación con citas, y negativa honesta cuando no hay respaldo

### Phase 7: Corte a producción
**Goal**: Apagar la v1 con la certeza de que nada se rompió y de que se puede volver atrás si algo sale mal.
**Depends on**: Phase 6
**Requirements**: OPS-06, OPS-07, UX-08, UX-09, UX-12
**Success Criteria** (what must be TRUE):
  1. La revisión con `Ariel-dev-standards`, `/gsd:code-review` y `/security-review` no deja hallazgos críticos ni altos abiertos
  2. La auditoría de UI y la de accesibilidad AA pasan en las pantallas principales, en modo claro y oscuro
  3. Todo el `UAT_CHECKLIST.md` está en verde, ejecutado por Raúl y por al menos una persona de RH
  4. Toda la aplicación es usable en teléfono, incluidos directorio y calendario
  5. Se puede completar el recorrido principal usando solo el teclado, con foco siempre visible
  6. Las páginas principales cargan en menos de 1.5 s en la red de oficina, y el directorio pagina o virtualiza en lugar de enviar 200 filas
  7. Las URLs de la v1 (`/directory`, `/calendar`, `/boletines`, `/document`, `/gallery`, `/humanResources`, `/enlaces`, `/iso`, `/birthdays`, `/aboutus`, `/reservationsummary`) redirigen a su equivalente en la v2
  8. El respaldo automático diario corre solo, y una restauración de prueba se completó con éxito
  9. Existe un runbook de despliegue y de reversión que alguien más podría seguir sin contexto
  10. La v1 queda archivada, no borrada, y se puede volver a levantar si hace falta
**Plans**: 4 plans

Plans:
- [ ] 07-01: Auditorías de código, seguridad, UI y accesibilidad, con corrección de hallazgos
- [ ] 07-02: Rendimiento: paginación o virtualización del directorio, índices, caché y medición
- [ ] 07-03: Migración final de datos, redirecciones desde las URLs de la v1, DNS y corte
- [ ] 07-04: Respaldos automáticos, runbook, archivado de la v1 y cierre del hito

## Notas de secuencia

- **La Fase 4 depende de la Fase 2, no de la 3.** Si en algún momento conviene mostrar comunicación antes que calendario, se pueden intercambiar 3 y 4 sin retrabajo.
- **La Fase 6 se puede adelantar parcialmente**: el índice de búsqueda sobre personas y documentos ya es útil terminando la Fase 4. Si Raúl quiere ⌘K antes, se parte la fase.
- **Nada depende del proveedor de AI.** Si nunca se autoriza el gasto, el proyecto se entrega completo salvo `SRCH-07`, `SRCH-08` y `SRCH-09`.
- **La v1 no se toca en ninguna fase salvo la 0** (limpieza de secretos) y la 7 (redirecciones y archivado).
