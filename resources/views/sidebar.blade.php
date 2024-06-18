<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            display: flex;
        }

        .sidebar {
            background-color: #333;
            color: #fff;
            width: 60px;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
            padding-top: 20px;
        }

        .sidebar .menu-item {
            color: #fff;
            padding: 15px 0;
            text-decoration: none;
            font-size: 20px;
        }

        .sidebar .menu-item:hover {
            background-color: #555;
        }

        .content {
            margin-left: 60px;
            width: calc(100% - 60px);
            padding: 20px;
        }

        .header {
    background-color: #ff0000;
    color: #fff;
    padding: 25px 0; /* Ajustado para asegurar el centrado vertical del título */
    text-align: center;
    width: 95%; /* Se extiende a todo el ancho */
    position: fixed;
    top: 0;
    left: 10;
    z-index: 1000; /* Asegura que el encabezado se mantenga sobre otros contenidos */
}

    .language-switcher {
    position: absolute; /* Posicionamiento absoluto respecto al padre relativo */
    right: 30px; /* Alinea a la derecha */
    top: 50%; /* Centra verticalmente */
    transform: translateY(-50%); /* Ajuste fino para el centrado vertical */
    }

    .language-switcher button {
    background-color: #fff;
    color: #333;
    border: none;
    cursor: pointer;
    padding: 5px 10px;
    margin-left: 5px;
    border-radius: 5px;
    }

        .banner, .news, .footer {
            background-color: #fff;
            margin: 20px 0;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .footer {
            text-align: center;
            color: #333;
        }

        /* Slideshow styles */
        .slideshow-container {
            max-width: 70%;
            position: relative;
            margin: 20px auto;
        }

        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }

        .slide {
            display: none;
        }

        .slide img {
            width: 100%;
            height: auto;
        }

/* Tooltip styles */
.menu-item {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
}

.menu-item[data-tooltip]::after, .menu-item[data-tooltip]::before {
    z-index: 1050; /* Asegura que los tooltips estén por encima de otros elementos */
}

.menu-item[data-tooltip]::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(-20px); /* Ajuste para mejor posicionamiento */
    white-space: nowrap;
    background-color: #333; /* Fondo oscuro para el tooltip */
    color: #fff; /* Texto blanco para el tooltip */
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
}

.menu-item:hover[data-tooltip]::after {
    opacity: 1;
    visibility: visible;
    transform: translateX(-25%) translateY(15px); /* Ligeramente más alto al pasar el mouse */
}

.menu-item[data-tooltip]::before {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(0);
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent; /* Color de flecha que coincide con el tooltip */
    opacity: 0; /* Inicialmente oculto */
    visibility: hidden; /* Inicialmente oculto */
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.menu-item:hover[data-tooltip]::before {
    opacity: 1; /* Visible al pasar el mouse */
    visibility: visible; /* Visible al pasar el mouse */
    transform: translateX(-50%) translateY(5px); /* Ajustado para mostrar debajo del tooltip */
}

    </style>
</head>
<body>

<div class="sidebar">
    <a href="#" class="menu-item" data-tooltip="Noticias"><i class="fas fa-newspaper"></i></a>
    <a href="#" class="menu-item" data-tooltip="Nuevos miembros"><i class="fas fa-user-friends"></i></a>
    <a href="{{ url('/calendar') }}" class="menu-item" data-tooltip="Calendario"><i class="fas fa-calendar-alt"></i></a>
    <a href="#" class="menu-item" data-tooltip="Galeria"><i class="fas fa-image"></i></a>
    <a href="#" class="menu-item" data-tooltip="Cumpleañeros"><i class="fas fa-birthday-cake"></i></a>
    <a href="#" class="menu-item" data-tooltip="Nuestra historia"><i class="fas fa-book"></i></a>
    <a href="#" class="menu-item" data-tooltip="Nomina"><i class="fas fa-wallet"></i></a>
    <a href="#" class="menu-item" data-tooltip="Recursos Humanos"><i class="fas fa-users"></i></a>
    <a href="#" class="menu-item" data-tooltip="Documentos"><i class="fas fa-file-alt"></i></a>
</div>

<div class="content">
    <div class="header">
        ARIEL WEST INTRANET
        <div class="language-switcher">
            <button onclick="changeLanguage('en')">EN</button>
            <button onclick="changeLanguage('es')">ES</button>
        </div>
    </div>

    <section class="banner">
        <h1 id="welcomeTitle">Bienvenido a tu espacio exclusivo de Nuestra Compañía</h1>
        <p id="welcomeText">Este es tu espacio, las últimas noticias, Únete, explora y contribuye a la vibrante atmósfera de nuestra compañía.</p>
    </section>

    <!-- Slideshow container -->
    <div class="slideshow-container">
        <div class="slide fade">
            <img src="routes/img/Ariel_Concert_1280x480.jpg" alt="Top News 1">
        </div>
        <div class="slide fade">
            <img src="https://via.placeholder.com/800x400?text=Top+News+2" alt="Top News 2">
        </div>
        <div class="slide fade">
            <img src="https://via.placeholder.com/800x400?text=Top+News+3" alt="Top News 3">
        </div>
    </div>

    <section class="news">
        <h2 id="latestNews">Últimas Noticias</h2>
        <div>
            <h3 id="newsTitle">Nos van a pagar el viernes</h3>
            <p id="newsDescription">Descripción de la noticia aquí. Esto podría ser una actualización, anuncio o cualquier cosa relevante para los empleados.</p>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 Ariel Premium LLC. Todos los derechos reservados.</p>
    </footer>
</div>

<script>
function changeLanguage(lang) {
    if (lang === 'en') {
        // Contenido y títulos
        document.getElementById('welcomeTitle').innerText = 'Welcome to Your Exclusive Space of Our Company';
        document.getElementById('welcomeText').innerText = 'This is your space, the latest news, Join, explore and contribute to the vibrant atmosphere of our company.';
        document.getElementById('latestNews').innerText = 'Latest News';
        document.getElementById('newsTitle').innerText = 'We will get paid on Friday';
        document.getElementById('newsDescription').innerText = 'News description here. This could be an update, announcement, or anything relevant to the employees.';

        // Tooltips
        document.querySelectorAll('.menu-item')[0].setAttribute('data-tooltip', 'News');
        document.querySelectorAll('.menu-item')[1].setAttribute('data-tooltip', 'New Members');
        document.querySelectorAll('.menu-item')[2].setAttribute('data-tooltip', 'Calendar');
        document.querySelectorAll('.menu-item')[3].setAttribute('data-tooltip', 'Gallery');
        document.querySelectorAll('.menu-item')[4].setAttribute('data-tooltip', 'Birthdays');
        document.querySelectorAll('.menu-item')[5].setAttribute('data-tooltip', 'Our Story');
        document.querySelectorAll('.menu-item')[6].setAttribute('data-tooltip', 'Payroll');
        document.querySelectorAll('.menu-item')[7].setAttribute('data-tooltip', 'Human Resources');
        document.querySelectorAll('.menu-item')[8].setAttribute('data-tooltip', 'Documents');
    } else if (lang === 'es') {
        // Contenido y títulos
        document.getElementById('welcomeTitle').innerText = 'Bienvenido a tu espacio exclusivo de Nuestra Compañía';
        document.getElementById('welcomeText').innerText = 'Este es tu espacio, las últimas noticias, Únete, explora y contribuye a la vibrante atmósfera de nuestra compañía.';
        document.getElementById('latestNews').innerText = 'Últimas Noticias';
        document.getElementById('newsTitle').innerText = 'Nos van a pagar el viernes';
        document.getElementById('newsDescription').innerText = 'Descripción de la noticia aquí. Esto podría ser una actualización, anuncio o cualquier cosa relevante para los empleados.';

        // Tooltips
        document.querySelectorAll('.menu-item')[0].setAttribute('data-tooltip', 'Noticias');
        document.querySelectorAll('.menu-item')[1].setAttribute('data-tooltip', 'Nuevos miembros');
        document.querySelectorAll('.menu-item')[2].setAttribute('data-tooltip', 'Calendario');
        document.querySelectorAll('.menu-item')[3].setAttribute('data-tooltip', 'Galería');
        document.querySelectorAll('.menu-item')[4].setAttribute('data-tooltip', 'Cumpleañeros');
        document.querySelectorAll('.menu-item')[5].setAttribute('data-tooltip', 'Nuestra historia');
        document.querySelectorAll('.menu-item')[6].setAttribute('data-tooltip', 'Nómina');
        document.querySelectorAll('.menu-item')[7].setAttribute('data-tooltip', 'Recursos Humanos');
        document.querySelectorAll('.menu-item')[8].setAttribute('data-tooltip', 'Documentos');
    }
}


    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("slide");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}
        slides[slideIndex-1].style.display = "block";
        setTimeout(showSlides, 4000); // Change image every 4 seconds
    }


</script>
</body>
</html>
