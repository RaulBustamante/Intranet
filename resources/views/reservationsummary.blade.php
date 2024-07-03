<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Reservaciones - ORIGAMI Intranet</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            color: #333;
        }
        header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 1rem 0;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .header-content {
            display: flex;
            align-items: center;
        }
        .logo {
            position: absolute;
            left: 1rem;
        }
        .logo img {
            width: 50px;
            height: auto;
        }
        .header-content h1 {
            margin: 0;
            font-size: 2rem;
        }
        .nav-bar {
            background-color: #34495e;
            padding: 1rem;
            text-align: center;
        }
        .nav-bar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .nav-bar ul li {
            display: inline;
        }
        .nav-bar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-bar ul li a:hover {
            background-color: #e74c3c;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .fc-toolbar-title {
            font-size: 1.5rem;
            color: #2c3e50;
        }
        .fc-button-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        .fc-button-primary:hover {
            background-color: #1a242f;
            border-color: #1a242f;
        }
        .fc-event {
            font-size: 0.9rem;
            padding: 5px;
        }
        .fc-daygrid-event-dot {
            border-radius: 50%;
            width: 10px;
            height: 10px;
        }
        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
                </a>
            </div>
            <h1>Resumen de Reservaciones de Salas</h1>
        </div>
    </header>

    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1">Soporte Técnico</a></li>
            <li><a href="/calendar">Calendario y Eventos</a></li>
            <li><a href="/humanResources">Recursos Humanos</a></li>
            <li><a href="/gallery">Galería de Eventos</a></li>
            <li><a href="https://masorden.com/">Más Orden</a></li>
            <li><a href="/boletines">Boletines Mensuales</a></li>
            <li><a href="/birthdays">Cumpleaños</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>

    <main class="container">
        <div class="form-group">
            <label for="roomFilter">Filtrar por Sala</label>
            <select class="form-control" id="roomFilter">
                <option value="all">Todas las Salas</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
            <button id="applyFilter" class="btn btn-primary">Aplicar</button>
        </div>
        </div>
        <div id="calendar"></div>
    </div>
    </div>
        <a href="{{ url('/reservations') }}" class="btn btn-success mt-3">Nueva Reservación</a>
    </main>

    <footer>
        <p>&copy; 2024 Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                events: @json($events),
                editable: false,
                droppable: false,
                eventRender: function(info) {
                    var tooltip = new Tooltip(info.el, {
                        title: info.event.title,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }
            });

            calendar.render();

            document.getElementById('applyFilter').addEventListener('click', function() {
                var roomId = document.getElementById('roomFilter').value;
                calendar.getEventSources().forEach(source => source.remove());
                calendar.addEventSource({
                    events: function(fetchInfo, successCallback, failureCallback) {
                        fetch('/api/reservations?room_id=' + roomId)
                            .then(response => response.json())
                            .then(data => successCallback(data));
                    }
                });
            });
        });
    </script>
</body>
</html>
