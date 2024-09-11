<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos - Ariel Hub</title>
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/company-logo.png') }}" alt="Company Logo">
                </a>
            </div>
            <h1>Ariel Hub - Subir Archivos</h1>
        </div>
    </header>

    <main>
        <section class="upload-file">
            <h2>Herramienta para subir un nuevo archivo</h2>

            <!-- Mensajes de éxito o error (aparecen por encima de los formularios) -->
            @if (session('galeria_success') || session('boletines_success') || session('documentos_success'))
                <p class="success-message">
                    {{ session('galeria_success') ?? session('boletines_success') ?? session('documentos_success') }}
                </p>
            @elseif (session('galeria_error') || session('boletines_error') || session('documentos_error'))
                <p class="error-message">
                    {{ session('galeria_error') ?? session('boletines_error') ?? session('documentos_error') }}
                </p>
            @endif

            <!-- Subida para Galerías -->
            <div>
                <h3>Galerías</h3>
                <form action="{{ route('upload.galeria') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="file_galeria">Seleccionar archivo (imagen o video):</label>
                    <input type="file" name="file_galeria" id="file_galeria" accept="video/*,image/*" required>
                    <br><br>
                    <button type="submit" class="upload-button">Subir Archivo a Galerías</button>
                </form>
            </div>

            <!-- Subida para Boletines -->
            <div>
                <h3>Boletines Mensuales</h3>
                <form action="{{ route('upload.boletines') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="file_boletines">Seleccionar archivo (solo PDF):</label>
                    <input type="file" name="file_boletines" id="file_boletines" accept=".pdf" required>
                    <br><br>
                    <button type="submit" class="upload-button">Subir Archivo a Boletines</button>
                </form>
            </div>

            <!-- Subida para Documentos (Recursos Humanos) -->
            <div>
                <h3>Documentos (Recursos Humanos)</h3>
                <form action="{{ route('upload.documentos') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="file_documentos">Seleccionar archivo (solo PDF):</label>
                    <input type="file" name="file_documentos" id="file_documentos" accept="video/*,image/*,.pdf" required>
                    <br><br>
                    <button type="submit" class="upload-button">Subir Archivo a Documentos</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 contacto de Intranet: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>

    <style>
    /* Estilos personalizados para la página de subida de archivos */
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

    main {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .upload-file {
        text-align: center;
    }

    .upload-file h2 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }

    label {
        font-size: 1rem;
        font-weight: bold;
        display: block;
        margin-bottom: 0.5rem;
    }

    input[type="file"] {
        display: inline-block;
        font-size: 1rem;
    }

    button.upload-button {
        background-color: #3498db;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    button.upload-button:hover {
        background-color: #2980b9;
    }

    .success-message, .error-message {
        margin-top: 1rem;
        font-size: 1.2rem;
        text-align: center;
    }

    .success-message {
        color: green;
    }

    .error-message {
        color: red;
    }

    footer {
        background-color: #2c3e50;
        color: #ecf0f1;
        text-align: center;
        padding: 1rem 0;
        margin-top: 2rem;
    }
    </style>
</body>
</html>
