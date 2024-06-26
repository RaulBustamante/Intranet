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
            <h1>Galería de Eventos</h1>
        </div>
    </header>
    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1">Soporte Técnico</a></li>
            <li><a href="/calendar">Calendario y Eventos</a></li>
            <li><a href="/humanResources">Recursos Humanos</a></li>
            <li><a href="/document">Documentos</a></li>
            <li><a href="https://masorden.com/">Más Orden</a></li>
            <li><a href="/boletines">Boletines Mensuales</a></li>
            <li><a href="/birthdays">Cumpleañeros</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>
    <main class="container">
        <section class="gallery">
            <h2>Eventos Pasados</h2>
            <div id="event-list" class="event-list">
                <!-- Eventos serán insertados aquí por JavaScript -->
            </div>
            <div id="pagination" class="pagination">
                <!-- Controles de paginación serán insertados aquí por JavaScript -->
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Galería de Eventos page loaded');

    const events = [
        { title: 'Evento de Verano 2023', description: 'Revive los mejores momentos del evento de verano.', link: 'path_to_document/evento_verano_2023.pdf' },
        { title: 'Fiesta de Navidad 2022', description: 'Recuerdos de nuestra fiesta de Navidad del año pasado.', link: 'path_to_document/fiesta_navidad_2022.pdf' },
        { title: 'Semana de la Salud 2022', description: 'Momentos destacados de la Semana de la Salud.', link: 'path_to_document/semana_salud_2022.pdf' },
        // Agregar más eventos según sea necesario
    ];

    const eventsPerPage = 6;
    let currentPage = 1;

    function displayEvents(page) {
        const eventList = document.getElementById('event-list');
        eventList.innerHTML = '';

        const start = (page - 1) * eventsPerPage;
        const end = start + eventsPerPage;
        const paginatedEvents = events.slice(start, end);

        paginatedEvents.forEach(event => {
            const eventItem = document.createElement('div');
            eventItem.className = 'event-item';
            eventItem.innerHTML = `
                <h3>${event.title}</h3>
                <p>${event.description}</p>
                <a href="${event.link}" target="_blank" class="button">Ver PDF</a>
            `;
            eventList.appendChild(eventItem);
        });
    }

    function setupPagination() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        const pageCount = Math.ceil(events.length / eventsPerPage);

        for (let i = 1; i <= pageCount; i++) {
            const button = document.createElement('button');
            button.innerText = i;
            button.addEventListener('click', () => {
                currentPage = i;
                displayEvents(currentPage);
                updatePagination();
            });
            pagination.appendChild(button);
        }
    }

    function updatePagination() {
        const buttons = document.querySelectorAll('.pagination button');
        buttons.forEach((button, index) => {
            if (index + 1 === currentPage) {
                button.disabled = true;
            } else {
                button.disabled = false;
            }
        });
    }

    displayEvents(currentPage);
    setupPagination();
    updatePagination();
});


</script>