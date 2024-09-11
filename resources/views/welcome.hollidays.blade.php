<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ariel - Hub</title>
    <link rel="stylesheet" href="{{ asset('css/intranet.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
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
            <h1>Ariel Hub</h1>
        </div>
    </header>

    <main>
        <section class="intro">
            <div class="orientation">
                <h2>¿Necesitas asistencia de RH?</h2>
                <p>Solicita tu consulta</p>
                <button onclick="window.open('https://wkf.ms/4bjg8Er', '_blank')">Solicitar</button>
                <div class="suggestions">
                    <h2>Buzón de Sugerencias</h2>
                    <p>Déjanos tus comentarios y sugerencias</p>
                    <button onclick="window.open('https://wkf.ms/4bjg8Er', '_blank')">Enviar Sugerencia</button>
                </div>
            </div>
            <div class="video">
                <video controls controlsList="nodownload">
                    <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
                    Tu navegador no soporta la etiqueta de video.
                </video>
                <p>Video del aniversario</p>
            </div>
            <div class="news">
                <h2>Últimas Noticias</h2>
                <ul>
                    <li><a href="{{ asset('documents/news1.pdf') }}" target="_blank">Croqueton 2024</a></li>
                    <li>
                        <a href="{{ route('video', ['title' => 'Ariel Olympics 2024', 'path' => 'https://d11dzzmlbj38ir.cloudfront.net/uploads/galerias/2024+Ariel\'s+Olympics.mp4']) }}" target="_blank">Ariel Olympics 2024</a>
                    </li>
                    <li>
                        <a href="{{ route('video', ['title' => 'Summer Fun Day 2024', 'path' => 'https://d11dzzmlbj38ir.cloudfront.net/uploads/galerias/20240818+Summer+Fun+Day.mp4']) }}" target="_blank">Summer Fun Day 2024</a>
                    </li>
                </ul>
            </div>
        </section>

        <section class="quick-links">
            <a href="https://forms.monday.com/forms/39c0137f606d1a26271cbe8e9372ada0?r=use1" target="_blank">
                <div class="icon">Soporte Técnico</div>
            </a>
            <a href="/calendar"><div class="icon">Calendario y Eventos</div></a>
            <a href="/humanResources"><div class="icon">Recursos Humanos</div></a>
            <a href="/document"><div class="icon">Documentos</div></a>
            <a href="/gallery"><div class="icon">Galería de Eventos</div></a>
            <a href="https://masorden.com/" target="_blank">
                <div class="icon">Más Orden</div>
            </a>
            <a href="/boletines"><div class="icon">Boletines Mensuales</div></a>
            <a href="/birthdays"><div class="icon">Cumpleaños</div></a>
            <a href="/directory"><div class="icon">Directorio</div></a>
            <a href="/enlaces"><div class="icon">Enlaces</div></a>
            <a href="/iso"><div class="icon">ISO</div></a>
            <a href="/reservationsummary"><div class="icon">Salas de juntas</div></a>
            <a href="/aboutus"><div class="icon">Sobre Ariel</div></a>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 contacto de Intranet: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
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

main {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.intro {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
    background-color: #ecf0f1;
    padding: 2rem;
    border-radius: 8px;
}

.orientation {
    flex: 1;
    text-align: center;
}

.orientation button {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 1rem;
}

.suggestions {
    margin-top: 2rem;
}

.video {
    flex: 2;
    text-align: center;
}

.video video {
    width: 100%;
    border-radius: 8px;
}

.news {
    flex: 1;
}

.news h2 {
    font-size: 1.5rem;
}

.news ul {
    list-style: none;
    padding: 0;
}

.news li {
    margin-bottom: 1rem;
}

.news a {
    color: #3498db;
    text-decoration: none;
}

.news a:hover {
    text-decoration: underline;
}

.referral-bonus {
    margin-top: 2rem;
}

.referral-bonus button {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
}

.quick-links {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin-top: 2rem;
    gap: 1rem;
}

.icon {
    background-color: #2c3e50;
    color: #ecf0f1;
    width: 120px;
    height: 120px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.icon:hover {
    background-color: #e74c3c;
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
    console.log('Intranet page loaded');

    // Prevent right-click on video to make downloading harder
    document.addEventListener('contextmenu', function (event) {
        var target = event.target;
        if (target.tagName === 'VIDEO') {
            event.preventDefault();
        }
    });

    const swiper = new Swiper('.swiper-container', {
        loop: true,
        slidesPerView: 1, // Muestra solo una diapositiva a la vez
        centeredSlides: true, // Centra la diapositiva
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 3000,
        },
    });
});
</script>
