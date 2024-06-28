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
        <section class="intro">
            <div class="intro-content">
                <h2>Bienvenidos a Ariel</h2>
                <p>En Ariel, nuestra misión es proporcionar una experiencia <strong>EXCEPCIONAL</strong> para nuestros Asociados, Clientes, y Proveedores.</p>
            </div>
        </section>
        <section class="mission-values">
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
                        <p>Compromiso con el crecimiento y la mejora porque aceptamos el cambio y bienvenidos oportunidades de aprender y liderar.</p>
                    </div>
                    <div class="value-item">
                        <h3>Comunicación</h3>
                        <p>Comunicación franca con respeto a través de compartir abiertamente opiniones e ideas mientras teniendo en cuenta la retroalimentación de otros.</p>
                    </div>
                    <div class="value-item">
                        <h3>Trabajo en Equipo</h3>
                        <p>Abrazar una diversidad de ideas, personas y sus talentos para lograr nuestros objetivos de la empresa.</p>
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
    background-color: #f0f4f8;
    color: #333;
}

header {
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 1rem 0;
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
    font-size: 2.5rem;
    font-weight: 600;
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
    gap: 1.5rem;
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

.intro {
    text-align: center;
    margin-bottom: 2rem;
}

.intro h2 {
    font-size: 2.5rem;
    color: #2c3e50;
}

.intro p {
    font-size: 1.2rem;
    line-height: 1.6;
    color: #555;
}

.mission-values {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    background-color: #ecf0f1;
    padding: 2rem;
    border-radius: 8px;
}

.mission img {
    max-width: 100%;
    border-radius: 8px;
}

.values {
    width: 100%;
}

.values h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
    text-align: center;
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
    transition: transform 0.3s, box-shadow 0.3s;
}

.value-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.value-item h3 {
    font-size: 1.6rem;
    margin-top: 0;
    color: #2c3e50;
}

.value-item p {
    font-size: 1rem;
    line-height: 1.6;
    color: #666;
}

.presentation {
    margin-top: 2rem;
    text-align: center;
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.presentation h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
}

.presentation .button {
    background-color: #e74c3c;
    color: #fff;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s ease-in-out;
}

.presentation .button:hover {
    background-color: #c0392b;
}

footer {
    background-color: #2c3e50;
    color: #ecf0f1;
    text-align: center;
    padding: 1rem 0;
}

footer a {
    color: #e74c3c;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Sobre Ariel page loaded');
});

</script>