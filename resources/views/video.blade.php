<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video - Ariel Hub</title>
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
            <h1>Ariel Hub - Video</h1>
        </div>
    </header>

    <main>
        <section class="video">
            <h2>{{ $videoTitle }}</h2>
            <video controls controlsList="nodownload" width="100%" height="auto">
                <source src="{{ $videoPath }}" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 contacto de Intranet: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>

    <script>
        // Prevenir clic derecho en el video para hacer más difícil la descarga
        document.addEventListener('contextmenu', function (event) {
            var target = event.target;
            if (target.tagName === 'VIDEO') {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

<style>
/* Puedes reutilizar el CSS de tu archivo principal o agregar estilos personalizados aquí */
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
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.video {
    text-align: center;
}

footer {
    background-color: #2c3e50;
    color: #ecf0f1;
    text-align: center;
    padding: 1rem 0;
}
</style>
