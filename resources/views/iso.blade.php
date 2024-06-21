<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO 9001:2015 - Intranet Ariel</title>
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <script src="{{ asset('js/intranet.js') }}" defer></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
            </div>
            <h1>ISO 9001:2015</h1>
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
            <li><a href="/birthdays">Cumpleañeros</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>
    <main class="container">
        <section class="quality-policy">
            <h2>Política de Calidad</h2>
            <img src="{{ asset('img/quality-policy.png') }}" alt="Política de Calidad">
            <p>La Política de Calidad representa el compromiso que los que laboramos en Veteran Promotions estamos contrayendo con nosotros mismos y con quienes servimos. Todos los procesos de Veteran Promotions están sujetos al cumplimiento de dicha Política y están manifestados en el Manual de Calidad. Sujeta también al proceso permanente de mejora continua, la Política de Calidad la hemos definido en los términos siguientes:</p>
            <blockquote>"Los colaboradores que formamos parte de 'Veteran Promotions' estamos comprometidos a exceder las expectativas de nuestros clientes, por medio de un sistema de gestión preventivo de la calidad que incluye la mejora de nuestros procesos".</blockquote>
        </section>
        <section class="quality-objectives">
            <h2>Objetivos de Calidad</h2>
            <ul>
                <li>Satisfacción del Cliente</li>
                <li>Ambiente de trabajo</li>
                <li>Mejora Continua</li>
            </ul>
        </section>
        <section class="iso-implementation">
            <h2>Implementación ISO 9001:2015</h2>
            <p>En nuestra búsqueda por la certificación ISO 9001:2015, hemos implementado varios procesos y procedimientos para asegurar el cumplimiento con los estándares de calidad. Algunos de estos procesos incluyen:</p>
            <ul>
                <li>Documentación y gestión de procesos</li>
                <li>Control de calidad y auditorías internas</li>
                <li>Capacitación y desarrollo del personal</li>
                <li>Evaluación de proveedores y mejora de la cadena de suministro</li>
                <li>Gestión de riesgos y oportunidades</li>
            </ul>
        </section>
        <section class="continuous-improvement">
            <h2>Mejora Continua</h2>
            <p>La mejora continua es un componente clave de nuestro sistema de gestión de calidad. A través de la implementación de ISO 9001:2015, nos comprometemos a:</p>
            <ul>
                <li>Identificar y eliminar ineficiencias en los procesos</li>
                <li>Promover la innovación y la creatividad en el lugar de trabajo</li>
                <li>Monitorear y evaluar el desempeño de los procesos regularmente</li>
                <li>Fomentar una cultura de calidad entre todos los empleados</li>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Intranet Ariel | Contacto: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
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

.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

section {
    background-color: #ecf0f1;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

section h2 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

section p, section ul {
    font-size: 1.2rem;
}

section ul {
    list-style: none;
    padding: 0;
}

section ul li {
    margin-bottom: 0.5rem;
}

blockquote {
    font-style: italic;
    margin: 1rem 0;
    padding: 1rem;
    background-color: #fff;
    border-left: 5px solid #2c3e50;
    border-radius: 4px;
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
    console.log('ISO 9001:2015 page loaded');
});


</script>