<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos - ORIGAMI Intranet</title>
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <script src="{{ asset('js/intranet.js') }}" defer></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
            </div>
            <h1>Documentos disponibles</h1>
        </div>
    </header>

    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="{{ url('/calendar') }}">Calendario</a></li>
            <li><a href="{{ url('/directory') }}">Directorio</a></li>
            <li><a href="{{ url('/birthdays') }}">Cumpleañeros</a></li>
            <li><a href="{{ url('/') }}">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <main class="container">
        <section class="documents-section">
            <div class="documents-header">
                <h2>Encuentra y descarga documentos aquí</h2>
                <p>Los documentos están disponibles en formatos PDF, Excel y Word.</p>
            </div>
            <div class="documents-list">
                <ul>
                    <li>
                        <span>Solicitud de Permiso.pdf</span>
                        <div class="document-actions">
                            <a href="path_to_document/Solicitud_de_Permiso.pdf" target="_blank" class="button">Leer</a>
                            <a href="path_to_document/Solicitud_de_Permiso.pdf" download class="button">Descargar</a>
                        </div>
                    </li>
                    <li>
                        <span>Reglamento Interno de Trabajo.pdf</span>
                        <div class="document-actions">
                            <a href="path_to_document/Reglamento_Interno_de_Trabajo.pdf" target="_blank" class="button">Leer</a>
                            <a href="path_to_document/Reglamento_Interno_de_Trabajo.pdf" download class="button">Descargar</a>
                        </div>
                    </li>
                    <li>
                        <span>Manual del Empleado.pdf</span>
                        <div class="document-actions">
                            <a href="path_to_document/Manual_del_Empleado.pdf" target="_blank" class="button">Leer</a>
                            <a href="path_to_document/Manual_del_Empleado.pdf" download class="button">Descargar</a>
                        </div>
                    </li>
                    <!-- Agrega más documentos según sea necesario -->
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

.documents-section {
    background-color: #ecf0f1;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.documents-header {
    text-align: center;
    margin-bottom: 2rem;
}

.documents-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.documents-list li {
    padding: 1rem;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.documents-list li:last-child {
    margin-bottom: 0;
}

.document-actions {
    display: flex;
    gap: 0.5rem;
}

.document-actions .button {
    background-color: #3498db;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.document-actions .button:hover {
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
    console.log('Documentos page loaded');
});

</script>