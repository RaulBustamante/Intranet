<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Eventos</title>
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <script src="{{ asset('js/intranet.js') }}" defer></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
                </a>
            </div>
            <h1>Calendario de Eventos</h1>
        </div>
    </header>

    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1">Soporte Técnico</a></li>
            <li><a href="/humanResources">Recursos Humanos</a></li>
            <li><a href="/document">Documentos</a></li>
            <li><a href="/gallery">Galería de Eventos</a></li>
            <li><a href="https://masorden.com/">Más Orden</a></li>
            <li><a href="/boletines">Boletines Mensuales</a></li>
            <li><a href="/birthdays">Cumpleañeros</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>

    <main>
        <section class="calendar">
            <div class="calendar-header">
                <h2>Calendario de Eventos 2024</h2>
            </div>
            <div class="calendar-content">
                @php
                    $holidays = [
                        '01-01' => ['Año Nuevo (USA, MX)', 'both'],
                        '05-02' => ['Día de la Constitución (MX)', 'mx'],
                        '18-03' => ['Natalicio de Benito Juarez (MX)', 'mx'],
                        '01-05' => ['Día del trabajo (MX)', 'mx'],
                        '27-05' => ['Memorial Day (USA)', 'usa'],
                        '04-07' => ['Independence Day (USA)', 'usa'],
                        '02-09' => ['Labor Day (USA)', 'usa'],
                        '16-09' => ['Día de la Independencia (MX)', 'mx'],
                        '01-10' => ['Día de la Transmisión del Poder Ejecutivo Federal (MX)', 'mx'],
                        '11-11' => ['Veterans Day (USA)', 'usa'],
                        '28-11' => ['Thanksgiving Day (USA)', 'usa'],
                        '29-11' => ['Thanksgiving Day (USA)', 'usa'],
                        '20-11' => ['Día de la Revolución Mexicana (MX)', 'mx'],
                        '25-12' => ['Navidad (USA, MX)', 'both']
                        
                    ];
                    $events = [
                        '30-03' => ['Semana Santa', 'event'],
                        '10-05' => ['Dia de las madres', 'event'],
                        '23-06' => ['Dia del padre', 'event'],
                        '15-08' => ['Paseo de Verano', 'event'],
                        '31-10' => ['Concurso de Halloween', 'event'],
                        '12-12' => ['Posada', 'event'],
                    ];
                    $months = [
                        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ];
                @endphp
                @for ($month = 1; $month <= 12; $month++)
                    <div class="month">
                        <h3>{{ $months[$month - 1] }}</h3>
                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th>D</th>
                                    <th>L</th>
                                    <th>M</th>
                                    <th>Mi</th>
                                    <th>J</th>
                                    <th>V</th>
                                    <th>S</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $date = DateTime::createFromFormat('Y-m-d', '2024-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01');
                                    $firstDayOfMonth = $date->format('N') % 7; // Ajuste para que el lunes sea el primer día de la semana
                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, 2024);
                                    $weeks = [];
                                    $week = array_fill(0, 7, '');
                                    for ($day = 1, $cell = $firstDayOfMonth; $day <= $daysInMonth; $day++, $cell++) {
                                        if ($cell == 7) {
                                            $weeks[] = $week;
                                            $week = array_fill(0, 7, '');
                                            $cell = 0;
                                        }
                                        $week[$cell] = $day;
                                    }
                                    $weeks[] = $week;
                                @endphp
                                @foreach ($weeks as $week)
                                    <tr>
                                        @foreach ($week as $day)
                                            @php
                                                $dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);
                                                $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
                                                $dateKey = "{$dayStr}-{$monthStr}";
                                                $todayClass = $day && $date->setDate(2024, $month, $day)->format('Y-m-d') == date('Y-m-d') ? 'today' : '';
                                                $holidayClass = isset($holidays[$dateKey]) ? $holidays[$dateKey][1] : '';
                                                $eventClass = isset($events[$dateKey]) ? 'event' : '';
                                            @endphp
                                            <td class="{{ $todayClass }} {{ $holidayClass }} {{ $eventClass }}">
                                                {{ $day ?: '' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endfor
            </div>
            <div class="event-list-container">
                <div class="event-list">
                    <h3>Festivos</h3>
                    <ul>
                        @foreach ($holidays as $date => $holiday)
                            <li>{{ $date }}: {{ $holiday[0] }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="event-list">
                    <h3>Eventos de la Compañía</h3>
                    <ul>
                        @foreach ($events as $date => $event)
                            <li>{{ $date }}: {{ $event[0] }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="legend">
                    <h3>Significado</h3>
                    <p><span class="legend-usa"></span> Festivos USA</p>
                    <p><span class="legend-mx"></span> Festivos MX</p>
                    <p><span class="legend-both"></span> Festivos USA y MX</p>
                    <p><span class="legend-event"></span> Eventos de la Compañía</p>
                    <p><span class="legend-today"></span> Día Actual</p> <!-- Añadir explicación para el color del día actual -->
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>
</body>
</html>

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

.calendar-header {
    text-align: center;
    margin: 2rem 0;
}

.calendar-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

.month {
    flex: 0 1 30%;
    margin: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #ecf0f1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.month h3 {
    background-color: #2c3e50;
    color: #ecf0f1;
    margin: 0;
    padding: 10px;
    text-align: center;
}

.calendar-table {
    width: 100%;
    border-collapse: collapse;
}

.calendar-table th, .calendar-table td {
    border: 1px solid #ddd;
    text-align: center;
    padding: 5px;
}

.calendar-table th {
    background-color: #34495e;
    color: #ecf0f1;
}

.calendar-table td.today {
    background-color: #e74c3c;
    color: #fff;
}

.calendar-table td.usa {
    background-color: #3498db;
    color: #fff;
}

.calendar-table td.mx {
    background-color: #2ecc71;
    color: #fff;
}

.calendar-table td.both {
    background: linear-gradient(45deg, #3498db 50%, #2ecc71 50%);
    color: #fff;
}

.calendar-table td.event {
    border: 2px solid #f39c12;
}

.event-list-container {
    display: flex;
    justify-content: space-around;
    margin: 2rem auto;
    max-width: 1200px;
}

.event-list, .legend {
    background-color: #ecf0f1;
    padding: 1rem;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 30%;
}

.event-list h3, .legend h3 {
    text-align: left;
    margin-bottom: 1rem;
}

.event-list ul {
    list-style: none;
    padding: 0;
}

.event-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #ddd;
}

.event-list li:last-child {
    border-bottom: none;
}

.legend p {
    margin: 0.5rem 0;
    text-align: left;
}

.legend span {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 10px;
    vertical-align: middle;
}

.legend-usa {
    background-color: #3498db;
}

.legend-mx {
    background-color: #2ecc71;
}

.legend-both {
    background: linear-gradient(45deg, #3498db 50%, #2ecc71 50%);
}

.legend-event {
    border: 2px solid #f39c12;
}

.legend-today {
    background-color: #e74c3c;
}

footer {
    background-color: #2c3e50;
    color: #ecf0f1;
    text-align: center;
    padding: 1rem 0;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Calendar page loaded');

    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('.calendar-table td').forEach(td => {
        if (td.textContent.trim()) {
            const monthName = td.closest('.month').querySelector('h3').textContent.trim();
            const year = 2024; // Puedes cambiar esto dinámicamente si es necesario
            const day = td.textContent.trim().padStart(2, '0');
            const monthNumber = new Date(Date.parse(monthName + " 1, 2024")).getMonth() + 1;
            const dateStr = `${year}-${String(monthNumber).padStart(2, '0')}-${day}`;
            if (dateStr === today) {
                td.classList.add('today');
            }
        }
    });
});
</script>
