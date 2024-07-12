<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos Humanos</title>
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
            <h1>Página de Recursos Humanos</h1>
            <p>Bienvenidos al portal de RRHH. Aquí encontrarás toda la información y recursos necesarios para tu carrera en nuestra empresa.</p>
        </div>
    </header>

    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1">Soporte Técnico</a></li>
            <li><a href="/calendar">Calendario y Eventos</a></li>
            <li><a href="/document">Documentos</a></li>
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
        <section id="beneficios">
            <div class="section-header">
                <h2>Beneficios</h2>
            </div>
            <div class="section-content">
                <p>Información detallada sobre los planes de salud, seguros, y otros beneficios.</p>
                <ul>
                    <li><a href="{{ url('/rh/plan-salud') }}">Plan de Salud</a></li>
                    <li><a href="{{ url('/rh/seguros-vida') }}">Seguros de Vida</a></li>
                    <li><a href="{{ url('/rh/bonos-desempeno') }}">Bonos de Desempeño</a></li>
                </ul>
            </div>
        </section>

        <section id="capacitacion">
            <div class="section-header">
                <h2>Reclutamiento</h2>
            </div>
            <div class="section-content">
                <p>Accede a recursos de formación y aprende más sobre oportunidades de desarrollo profesional.</p>
                <ul>
                    <li><a href="{{ url('/rh/cursos-en-linea') }}">Vacantes disponibles</a></li>
                    <li><a href="{{ url('/rh/talleres-presenciales') }}">Programa de Referidos</a></li>
                </ul>
            </div>
        </section>
        
        <section id="capacitacion">
            <div class="section-header">
                <h2>Capacitación y Desarrollo</h2>
            </div>
            <div class="section-content">
                <p>Accede a recursos de formación y aprende más sobre oportunidades de desarrollo profesional.</p>
                <ul>
                    <li><a href="{{ url('/rh/cursos-en-linea') }}">Vacantes disponibles</a></li>
                    <li><a href="{{ url('/rh/talleres-presenciales') }}">Programa de Referidos</a></li>
                    <li><a href="{{ url('/rh/programas-mentoria') }}">Programas de mentoría</a></li>
                </ul>
            </div>
        </section>

        <section id="bienestar">
            <div class="section-header">
                <h2>Bienestar del Empleado</h2>
            </div>
            <div class="section-content">
                <p>Explora programas y recursos para mantener un estilo de vida saludable.</p>
                <ul>
                    <li><a href="{{ url('/rh/programas-ejercicio') }}">Programas de ejercicio</a></li>
                    <li><a href="{{ url('/rh/charlas-nutricion') }}">Charlas de nutrición</a></li>
                    <li><a href="{{ url('/rh/asistencia-psicologica') }}">Asistencia psicológica</a></li>
                </ul>
            </div>
        </section>

        <section id="bienestar">
            <div class="section-header">
                <h2>Contabilidad</h2>
            </div>
            <div class="section-content">
                <p>Todo lo referente a tus nominas.</p>
                <ul>
                    <li><a href="{{ asset('documents/hr/sodexo.pdf') }}">Preguntas frecuentes de Sodexo - Pluxie</a></li>
                </ul>
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
    flex-direction: column;
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

.header-content p {
    font-size: 1rem;
    color: #bdc3c7;
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

section {
    background-color: #ecf0f1;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.section-header {
    text-align: left;
    border-bottom: 2px solid #e74c3c;
    margin-bottom: 1rem;
}

.section-header h2 {
    margin: 0;
    padding-bottom: 0.5rem;
    color: #2c3e50;
}

.section-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.section-content ul li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #ddd;
}

.section-content ul li:last-child {
    border-bottom: none;
}

.section-content ul li a {
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s;
}

.section-content ul li a:hover {
    color: #2980b9;
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
    console.log('Recursos Humanos page loaded');
});
</script>