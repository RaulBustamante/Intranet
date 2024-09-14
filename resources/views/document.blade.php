<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <title>Documentos - Intranet</title>
    <style>
        /* Estilos generales */
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

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        /* Menú de navegación */
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

        /* Estilo del listado de documentos */
        .documents-section {
            background-color: #ffffff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .documents-section h2 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        .documents-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .documents-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .documents-list li:last-child {
            margin-bottom: 0;
        }

        .documents-list li span {
            font-size: 1.1rem;
            font-weight: 600;
            color: #34495e;
            max-width: 70%; /* Limita el ancho del nombre del documento */
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
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
            font-size: 0.9rem;
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
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
                </a>
            </div>
            <h1>Documentos disponibles</h1>
        </div>
    </header>
    
    <!-- Menú de navegación -->
    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1" target="_blank">Soporte Técnico</a></li>
            <li><a href="/calendar">Calendario y Eventos</a></li>
            <li><a href="/humanResources">Recursos Humanos</a></li>
            <li><a href="/document">Documentos</a></li>
            <li><a href="/gallery">Galería de Eventos</a></li>
            <li><a href="https://masorden.com/" target="_blank">Más Orden</a></li>
            <li><a href="/boletines">Boletines Mensuales</a></li>
            <li><a href="/birthdays">Cumpleaños</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>

    <main class="container">
        <section class="documents-section">
            <h2>Lista de documentos</h2>
            <div class="documents-list">
                <ul>
                    @foreach ($files as $file)
                        <li>
                            <span>{{ $file['name'] }}</span>
                            <div class="document-actions">
                                <a href="{{ $file['url'] }}" target="_blank" class="button">Leer</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>
</body>
</html>
