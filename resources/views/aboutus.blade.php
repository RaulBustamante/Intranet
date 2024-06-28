<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <title>Sobre Ariel - Intranet</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
                </a>
            </div>
            <h1>Sobre Ariel</h1>
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
            <li><a href="/birthdays">Cumpleaños</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
        </ul>
    </nav>
    <main class="container">
        <section class="mission-values">
            <div class="mission">
                <h2>Nuestra Misión</h2>
                <p>Proporcionar una experiencia <strong>EXCEPCIONAL</strong> para nuestros Asociados, Clientes, y Proveedores.</p>
            </div>
            <div class="values">
                <h2>Nuestros Valores</h2>
                <div class="values-content">
                    <div class="value-item">
                        <h3>Integridad</h3>
                        <p>Actuando consistentemente con honestidad, responsabilidad y lealtad a unos a otros, y nuestros clientes.</p>
                    </div>
                    <div class="value-item">
                        <h3>Asociación</h3>
                        <p>Como resultado de la construcción a largo plazo relaciones con los proveedores y distribuidores, haciendo hincapié en comunicación abierta y honesta y flexibilidad.</p>
                    </div>
                    <div class="value-item">
                        <h3>Crecimiento</h3>
                        <p>Compromiso con el crecimiento y la mejora por que aceptamos el cambio y bienvenidos oportunidades de aprender y liderar.</p>
                    </div>
                    <div class="value-item">
                        <h3>Comunicación</h3>
                        <p>Comunicación franca con respeto a través de compartir abiertamente opiniones e ideas mientras teniendo en cuenta la retroalimentación de otros.</p>
                    </div>
                    <div class="value-item">
                        <h3>Trabajo en Equipo</h3>
                        <p>Abrazar una diversidad de ideas, personas y sus talentos para Lograr Nuestro objetivos de la empresa.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="presentation">
            <h2>Presentación de Bienvenida</h2>
            <a href="{{ asset('documents/welcome.pdf') }}" target="_blank" class="button">Ver Presentación de Bienvenida</a>
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

.mission-values {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
    background-color: #ecf0f1;
    padding: 2rem;
    border-radius: 8px;
}

.mission, .values {
    flex: 1;
}

.mission h2, .values h2 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

.mission p {
    font-size: 1.2rem;
}

.values-content {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.value-item {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 1rem;
    flex: 1 1 calc(50% - 1rem);
}

.value-item h3 {
    font-size: 1.4rem;
    margin-top: 0;
}

.value-item p {
    font-size: 1rem;
}

.presentation {
    margin-top: 2rem;
    text-align: center;
}

.presentation h2 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

.presentation .button {
    background-color: #3498db;
    color: #fff;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease-in-out;
}

.presentation .button:hover {
    background-color: #2980b9;
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
    console.log('Sobre Ariel page loaded');
});

</script>