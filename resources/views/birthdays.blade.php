<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cumpleaños de Empleados</title>
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
            <h1>Lista de Cumpleaños de Empleados</h1>
        </div>
    </header>

    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1">Soporte Técnico</a></li>
            <li><a href="/calendar">Calendario y Eventos</a></li>
            <li><a href="/humanResources">Recursos Humanos</a></li>
            <li><a href="/document">Documentos</a></li>
            <li><a href="/gallery">Galería de Eventos</a></li>
            <li><a href="https://masorden.com/">Más Orden</a></li>
            <li><a href="/boletines">Boletines Mensuales</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>

    <main>
        <section class="birthdays">
            <!-- Enero -->
            <div class="month" onclick="toggleList('january')">Enero</div>
            <ul id="january" class="birthday-list">
                <li>Juan Perez - Enero 5</li>
                <li>Maria Lopez - Enero 18</li>
            </ul>

            <!-- Febrero -->
            <div class="month" onclick="toggleList('february')">Febrero</div>
            <ul id="february" class="birthday-list">
                <li>Carlos Jimenez - Febrero 10</li>
            </ul>

            <!-- Marzo -->
            <div class="month" onclick="toggleList('march')">Marzo</div>
            <ul id="march" class="birthday-list">
                <li>Pedro Martinez - Marzo 12</li>
            </ul>

            <!-- Abril -->
            <div class="month" onclick="toggleList('april')">Abril</div>
            <ul id="april" class="birthday-list">
                <li>Ana Torres - Abril 22</li>
            </ul>

            <!-- Mayo -->
            <div class="month" onclick="toggleList('may')">Mayo</div>
            <ul id="may" class="birthday-list">
                <li>Sofia Gomez - Mayo 5</li>
            </ul>

            <!-- Junio -->
            <div class="month" onclick="toggleList('june')">Junio</div>
            <ul id="june" class="birthday-list">
                <li>Jesus Erasmo Santos - 5 - Produccion</li>
                <li>Jesus Guadalupe Lopez - 6 - Produccion</li>
                <li>Jazmin Dominguez - 6 - Produccion</li>
                <li>Jazmin Diaz - 9 - Envios</li>
                <li>Ana Cecilia Diaz - 10 - Produccion</li>
                <li>Pablo Mandujano - 11 - Produccion</li>
                <li>Laura Isabel Lopez - 13 - Arte</li>
                <li>Maria Antonia Aguilar - 13 - Produccion</li>
                <li>Irma Toledo - 18 - Produccion Ins</li>
                <li>Jose Velazquez - 19 - Sistemas</li>
                <li>Jesus Serrano - 20 - Produccion Ins</li>
                <li>Guadalupe Rodriguez - 21 - Produccion</li>
                <li>Yasbeck Cardenas - 21 - Arte</li>
                <li>Juan Manuel Rosales - 22 - Produccion</li>
                <li>Mayra Moran - 26 - Marketing</li>
                <li>Hector Zapata - 30 - DS</li>
                <li>Tomas Camacho - 30 - Produccion</li>
            </ul>

            <!-- Julio -->
            <div class="month" onclick="toggleList('july')">Julio</div>
            <ul id="july" class="birthday-list">
                <li>Diego Sanchez - Julio 19</li>
            </ul>

            <!-- Agosto -->
            <div class="month" onclick="toggleList('august')">Agosto</div>
            <ul id="august" class="birthday-list">
                <li>Laura Ruiz - Agosto 23</li>
            </ul>

            <!-- Septiembre -->
            <div class="month" onclick="toggleList('september')">Septiembre</div>
            <ul id="september" class="birthday-list">
                <li>Antonio Vargas - Septiembre 8</li>
            </ul>

            <!-- Octubre -->
            <div class="month" onclick="toggleList('october')">Octubre</div>
            <ul id="october" class="birthday-list">
                <li>Andrea Perez - Octubre 17</li>
            </ul>

            <!-- Noviembre -->
            <div class="month" onclick="toggleList('november')">Noviembre</div>
            <ul id="november" class="birthday-list">
                <li>Raul Herrera - Noviembre 3</li>
            </ul>

            <!-- Diciembre -->
            <div class="month" onclick="toggleList('december')">Diciembre</div>
            <ul id="december" class="birthday-list">
                <li>Monica Diaz - Diciembre 25</li>
            </ul>
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

.birthdays {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.month {
    background: #2c3e50;
    color: white;
    padding: 10px 20px;
    margin: 10px 0;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.month:hover {
    background: #e74c3c;
}

.birthday-list {
    list-style: none;
    padding: 0;
    display: none;
    background: white;
    border-radius: 5px;
    margin-top: 5px;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.birthday-list li {
    padding: 5px 0;
    border-bottom: 1px solid #ccc;
}

.birthday-list li:last-child {
    border-bottom: none;
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
    console.log('Birthday page loaded');

    const toggleList = (id) => {
        const list = document.getElementById(id);
        list.style.display = list.style.display === 'block' ? 'none' : 'block';
    };

    // Attach event listeners to each month div
    document.querySelectorAll('.month').forEach(month => {
        month.addEventListener('click', () => {
            toggleList(month.getAttribute('onclick').replace('toggleList(', '').replace(')', '').replace(/'/g, ''));
        });
    });
});
</script>
