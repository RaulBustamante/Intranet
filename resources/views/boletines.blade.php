<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <title>Galería de Eventos - Intranet</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
                </a>
            </div>
            <h1>Boletines mensuales</h1>
        </div>
    </header>
    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1" target="_blank">Soporte Técnico</a></li>
            <li><a href="/calendar">Calendario y Eventos</a></li>
            <li><a href="/humanResources">Recursos Humanos</a></li>
            <li><a href="/document">Documentos</a></li>
            <li><a href="/gallery">Galería de Eventos</a></li>
            <li><a href="https://masorden.com/" target="_blank">Más Orden</a></li>
            <li><a href="/birthdays">Cumpleaños</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>
    <main class="container">
        <section class="gallery">
            <h2>Boletines mensuales</h2>
            <div id="event-list" class="event-list">
                <div class="event-item">
                    <h3>Marzo 2024</h3>
                    <p>Comunicados Organizacionales de Marzo.</p>
                    <a href="{{ asset('documents/boletines/comunicado.03.24.pdf') }}" target="_blank" class="button">Ver PDF</a>
                </div>
                <div class="event-item">
                    <h3>Abril 2024</h3>
                    <p>Comunicados Organizacionales de Abril.</p>
                    <a href="{{ asset('documents/boletines/comunicado.04.24.pdf') }}" target="_blank" class="button">Ver PDF</a>
                </div>
                <div class="event-item">
                    <h3>Mayo 2024</h3>
                    <p>Comunicados Organizacionales de Mayo.</p>
                    <a href="{{ asset('documents/boletines/comunicado.05.24.pdf') }}" target="_blank" class="button">Ver PDF</a>
                </div>
                <div class="event-item">
                    <h3>Junio 2024</h3>
                    <p>Comunicados Organizacionalesw de Junio.</p>
                    <a href="{{ asset('documents/boletines/comunicado.06.24.pdf') }}" target="_blank" class="button">Ver PDF</a>
                </div>
                <!-- Agregar más eventos según sea necesario -->
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>
    <script src="{{ asset('js/intranet.js') }}" defer></script>
</body>
</html>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
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

.gallery {
    text-align: center;
}

.gallery h2 {
    font-size: 2rem;
    margin-bottom: 2rem;
}

.event-list {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
}

.event-item {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 1rem;
    width: calc(33% - 2rem);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: transform 0.3s ease-in-out;
}

.event-item:hover {
    transform: scale(1.05);
}

.event-item h3 {
    font-size: 1.5rem;
    color: #2c3e50;
}

.event-item p {
    font-size: 1rem;
    color: #666;
    margin-bottom: 1rem;
}

.button {
    background-color: #3498db;
    color: #fff;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease-in-out;
}

.button:hover {
    background-color: #2980b9;
}

.pagination {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.pagination button {
    background-color: #3498db;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

.pagination button:hover {
    background-color: #2980b9;
}

.pagination button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

footer {
    background-color: #2c3e50;
    color: #ecf0f1;
    text-align: center;
    padding: 1rem 0;
}
</style>
