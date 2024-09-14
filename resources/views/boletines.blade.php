<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <title>Boletines mensuales</title>
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
            gap: 2rem; /* Ajustamos el espacio entre las tarjetas */
            justify-content: center;
        }

        .event-item {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 1rem;
            width: calc(20% - 1rem); /* Más pequeño */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease-in-out;
            word-wrap: break-word;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-item:hover {
            transform: scale(1.05);
        }

        .event-item h3 {
            font-size: 1.1rem; /* Tamaño más pequeño */
            color: #2c3e50;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .button {
            background-color: #3498db;
            color: #fff;
            padding: 0.5rem 0.75rem; /* Botón más pequeño */
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
            font-size: 0.8rem; /* Tamaño de letra más pequeño */
        }

        .button:hover {
            background-color: #2980b9;
        }

        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 1rem 0;
        }

        /* Estilos adicionales para ajustar el contenido */
        .event-item h3 {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Limita el texto a 2 líneas */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-item a {
            display: block;
            margin-top: 0.5rem;
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
            <h1>Boletines mensuales</h1>
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
        <section class="gallery">
            <h2>Boletines mensuales</h2>
            <div id="event-list" class="event-list">
                @foreach ($files as $file)
                    <div class="event-item">
                        <h3>{{ $file['name'] }}</h3>
                        <a href="{{ $file['url'] }}" target="_blank" class="button">Ver PDF</a>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>
</body>
</html>
