<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Eventos - Intranet</title>
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            color: #333;
        }

        /* Cabecera */
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

        /* Navegación */
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

        /* Estilos del main */
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* Título */
        .gallery h2 {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            color: #34495e;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #e74c3c;
            display: inline-block;
            padding-bottom: 10px;
        }

        /* Estilos de las tarjetas de la lista */
        #event-list ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem; /* Aumentar el espacio entre las tarjetas */
        }

        #event-list li {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            text-align: center;
            width: 300px; /* Ajustar ancho fijo */
            max-width: 300px; /* Prevenir que se estiren demasiado */
            word-wrap: break-word; /* Evitar que el texto salga de la tarjeta */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis; /* Corta el texto largo con "..." */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #event-list li:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        #event-list a {
            text-decoration: none;
            color: #3498db;
            font-weight: 600;
            font-size: 1.1rem;
            display: block;
            padding: 0.5rem 0;
            transition: color 0.3s ease;
            white-space: normal; /* Permitir múltiples líneas si el texto es muy largo */
        }

        #event-list a:hover {
            color: #e74c3c;
        }

        /* Footer */
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
            <h1>Galería de Eventos</h1>
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
            <li><a href="/boletines">Boletines Mensuales</a></li>
            <li><a href="/birthdays">Cumpleaños</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>

    <main>
        <section class="gallery">
            <h2>Eventos Recientes</h2>
            <div id="event-list">
                <ul>
                    @foreach ($files as $file)
                        <li>
                            @if(in_array($file['extension'], ['mp4', 'mov', 'ogg', 'qt']))
                                <a href="{{ route('video', ['title' => $file['name'], 'path' => $file['url']]) }}" target="_blank">
                                    {{ $file['name'] }} (Video)
                                </a>
                            @elseif(in_array($file['extension'], ['pdf', 'jpg', 'jpeg', 'png']))
                                <a href="{{ $file['url'] }}" target="_blank">
                                    {{ $file['name'] }} (Abrir en nueva pestaña)
                                </a>
                            @else
                                {{ $file['name'] }} (Formato no soportado)
                            @endif
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
