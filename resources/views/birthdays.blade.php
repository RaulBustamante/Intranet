<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cumpleaños de Empleados</title>
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
            <h1>Lista de Cumpleaños de Empleados</h1>
        </div>
    </header>

    <nav class="nav-bar">
        <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="https://helpme.arielapps.net/open.php">Soporte Técnico</a></li>
            <li><a href="/calendar">Calendario y Eventos</a></li>
            <li><a href="/humanResources">Recursos Humanos</a></li>
            <li><a href="/document">Documentos</a></li>
            <li><a href="/gallery">Galería de Eventos</a></li>
            <li><a href="https://masorden.com/">Más Orden</a></li>
            <li><a href="/boletines">Boletines Mensuales</a></li>
            <li><a href="/directory">Directorio</a></li>
            <li><a href="/enlaces">Enlaces</a></li>
            <li><a href="/iso">ISO</a></li>
            <li><a href="/aboutus">Sobre Ariel</a></li>
        </ul>
    </nav>

    <main>
        <section class="birthdays">
            <!-- Enero -->
            <div class="month" onclick="toggleList('january')">Enero</div>
            <ul id="january" class="birthday-list">
               <li>Samuel Vazquez - Enero 3</li>
                <li>Alan Rodriguez - Enero 3</li>
                <li>Patricia Amador - Enero 6</li>
                <li>Karen Villa - Enero 6</li>
                <li>Sandra Reynoso - Enero 7</li>
                <li>Jesus Maravilla - Enero 9</li>
                <li>Brandon Delgado - Enero 9</li>
                <li>Omar Monreal - Enero 13</li>
                <li>Kevin Cervantes - Enero 13</li>
                <li>Adolfo Dominguez - Enero 17</li>
                <li>Mario Velarde - Enero 19</li>
                <li>Sujey Colin - Enero 21</li>
                <li>Rosa Hernández - Enero 22</li>
                <li>Alfredo Inzunza - Enero 23</li>
                <li>Yesenia Ventura - Enero 23</li>
                <li>Brenda Melchor - Enero 23</li>
                <li>Elvira Nuño - Enero 25</li>
                <li>Vanessa Garcia - Enero 30</li>
                <li>Said Medina - Enero 30</li>
                <li>Claudia Rivas - Enero 31</li>
                <li>Jesús Rodriguez - Enero 31</li>
            </ul>

            <!-- Febrero -->
            <div class="month" onclick="toggleList('february')">Febrero</div>
            <ul id="february" class="birthday-list">
                <li>Ana Sandoval - Febrero 2</li>
                <li>Cesar Pizaña - Febrero 2</li>
                <li>Diana De la Cerda - Febrero 3</li>
                <li>Alfredo Ramirez - Febrero 4</li>
                <li>Josué Argote - Febrero 4</li>
                <li>Concepción Castillo - Febrero 5</li>
                <li>José Tavera - Febrero 5</li>
                <li>Fernanda Osuna - Febrero 5</li>
                <li>Luz Hernández - Febrero 7</li>
                <li>Vannesa Martinez - Febrero 14</li>
                <li>Juan Almanza - Febrero 17</li>
                <li>Simona Solis - Febrero 18</li>
                <li>Guadalupe Herrera - Febrero 20</li>
                <li>Johan Mendes - Febrero 20</li>
                <li>Grisly López - Febrero 21</li>
                <li>Blanca Reynoso - Febrero 24</li>
                <li>Jennifer Sil - Febrero 26</li>
                <li>Ricardo Moran - Febrero 26</li>
                <li>Patricia Martinez - Febrero 27</li>
            </ul>

            <!-- Marzo -->
            <div class="month" onclick="toggleList('march')">Marzo</div>
    <ul id="march" class="birthday-list">
        <li>Tomas Camacho - Marzo 2 (Almacén)</li>
        <li>Lilia Ibarra - Marzo 2 (Producción)</li>
        <li>Raul Bustamante - Marzo 4 (Administración)</li>
        <li>Leticia López - Marzo 5 (Producción)</li>
        <li>Humberto Mares - Marzo 6 (Producción)</li>
        <li>Jorge Miranda - Marzo 6 (Producción)</li>
        <li>Daniel Guzmán - Marzo 7 (Diseño)</li>
        <li>Karely Valdez - Marzo 8 (Producción)</li>
        <li>Jorge Cuellar - Marzo 9 (Almacén)</li>
        <li>Jesus Soto - Marzo 11 (Producción)</li>
        <li>Alma Cardenas - Marzo 15 (Producción)</li>
        <li>Alfonso Orozco - Marzo 16 (Producción Murua 21)</li>
        <li>Joanna Pacheco - Marzo 17 (Producción)</li>
        <li>José Luis Martinez - Marzo 17 (Diseño)</li>
        <li>Zayanna Silvestre - Marzo 18 (Calidad)</li>
        <li>José Luis Hidrogo - Marzo 19 (Envíos)</li>
        <li>Luteria González - Marzo 21 (Intendencia)</li>
        <li>Brenda Casas - Marzo 21 (Producción)</li>
        <li>Stephany Ramirez - Marzo 23 (DS)</li>
        <li>Ma. De la Luz Quiñonez - Marzo 24 (Producción Murua 21)</li>
        <li>Olandina González - Marzo 24 (Producción Murua 21)</li>
        <li>Norberto Alvarado - Marzo 26 (Producción)</li>
        <li>Rolando López - Marzo 26 (Producción)</li>
        <li>Alfredo Morales - Marzo 27 (RH)</li>
        <li>Sebastian Cruz - Marzo 27 (Almacén)</li>
        <li>Luis Alvarado - Marzo 30</li>
    </ul>

            <!-- Abril -->
            <div class="month" onclick="toggleList('april')">Abril</div>
            <ul id="april" class="birthday-list">
                <li>Ricardo Vazquez - Abril 3 (Producción)</li>
                <li>Luis Cames - Abril 5 (Producción)</li>
                <li>Raquel Garza - Abril 8 (Producción)</li>
                <li>Evelyne Chacón - Abril 8 (Producción)</li>
                <li>Monica Gamboa - Abril 9 (Producción)</li>
                <li>Santiago Lizárraga - Abril 9 (Almacén Nocturno)</li>
                <li>Isaac Sevilla - Abril 10 (Almacén)</li>
                <li>Reina Burrola - Abril 12 (Arte)</li>
                <li>Evelin Quezada - Abril 13 (Compras)</li>
                <li>Yesenia Fonseca - Abril 15 (Producción)</li>
                <li>Melisa Velazquez - Abril 15 (Producción)</li>
                <li>Priscilla Castillo - Abril 17 (Marketing)</li>
                <li>Magaly Fierro - Abril 18 (Producción)</li>
                <li>Roberto Gutierrez - Abril 20 (DS)</li>
                <li>Judith Zavala - Abril 22 (Contabilidad)</li>
                <li>Itzuri Garniça - Abril 24 (Producción Murua 21)</li>
                <li>Marcos Perez - Abril 25 (Producción)</li>
                <li>Mayra Ambriz - Abril 25 (DS)</li>
                <li>Elizabeth Loera - Abril 28 (Producción)</li>
            </ul>

            <!-- Mayo -->
            <div class="month" onclick="toggleList('may')">Mayo</div>
            <ul id="may" class="birthday-list">
                <li>Maria Cruz González - Mayo 3</li>
                <li>Adriana Isaias - Mayo 4</li>
                <li>Mariana Baños - Mayo 5</li>
                <li>Karina Hernández - Mayo 7</li>
                <li>Luis Antonio Morales - Mayo 7</li>
                <li>Alejandro Zamudio - Mayo 8</li>
                <li>Veronica Cortez - Mayo 8</li>
                <li>Irma Benitez - Mayo 9</li>
                <li>Cristo Galván - Mayo 10</li>
                <li>Alejandrina Zamora - Mayo 11</li>
                <li>Kevin Sotelo - Mayo 11</li>
                <li>Daniel Palomares - Mayo 13</li>
                <li>Ana Paola Ramirez - Mayo 15</li>
                <li>Ricardo Ubaldo - Mayo 21</li>
                <li>Susan Molina - Mayo 22</li>
                <li>Erick Hernández - Mayo 24</li>
                <li>Laisa Vera - Mayo 24</li>
                <li>Edgar Mares - Mayo 25</li>
                <li>Juan Carlos Piña - Mayo 28</li>
                <li>José Luis Ponce - Mayo 28</li>
                <li>José Luis Becerra - Mayo 30</li>
                <li>Joseline Torres - Mayo 30</li>
                <li>Samuel Montes - Mayo 31</li>
                <li>Esmeralda Alarcón - Mayo 31</li>
            </ul>

            <!-- Junio -->
            <div class="month" onclick="toggleList('june')">Junio</div>
            <ul id="june" class="birthday-list">
                <li>Jesus Erasmo Santos - 5 - Produccion</li>
                <li>Jesus Guadalupe Lopez - 6 - Produccion</li>
                <li>Jazmin Dominguez - 6 - Produccion</li>
                <li>Jazmin Diaz - 9 - Envios</li>
                <li>Ana Cecilia Diaz - 10 - Produccion</li>
                <li>Pablo Mandujano - 11 - Produccion</li>
                <li>Laura Isabel Lopez - 13 - Arte</li>
                <li>Maria Antonia Aguilar - 13 - Produccion</li>
                <li>Irma Toledo - 18 - Produccion Ins</li>
                <li>Jose Velazquez - 19 - Sistemas</li>
                <li>Jesus Serrano - 20 - Produccion Ins</li>
                <li>Guadalupe Rodriguez - 21 - Produccion</li>
                <li>Yasbeck Cardenas - 21 - Arte</li>
                <li>Juan Manuel Rosales - 22 - Produccion</li>
                <li>Mayra Moran - 26 - Marketing</li>
                <li>Hector Zapata - 30 - DS</li>
                <li>Tomas Camacho - 30 - Produccion</li>
            </ul>

            <!-- Julio -->
            <div class="month" onclick="toggleList('july')">Julio</div>
            <ul id="july" class="birthday-list">
                <li>Ana Parra - Julio 3</li>
                <li>Karina Valenzuela - Julio 5</li>
                <li>Jose Partida - Julio 5</li>
                <li>Ana Galindo - Julio 8</li>
                <li>Alejandra Contreras - Julio 11</li>
                <li>Jacquelyn Solano - Julio 11</li>
                <li>Perla Vazquez - Julio 13</li>
                <li>Kenia Suarez - Julio 13</li>
                <li>Isela Ramirez - Julio 16</li>
                <li>Enrique Sandoval - Julio 16</li>
                <li>Dajan Paul Rodriguez - Julio 19</li>
                <li>Diana Espinoza - Julio 20</li>
                <li>Alejandro Merino - Julio 23</li>
                <li>Laura Villalobos - Julio 23</li>
                <li>Gerardo Avalos - Julio 29</li>
                <li>Ricardo Mendoza - Julio 29</li>
                <li>Martha Molina - Julio 29</li>
            </ul>

            <!-- Agosto -->
            <div class="month" onclick="toggleList('august')">Agosto</div>
            <ul id="august" class="birthday-list">
                <li>Laura Ruiz - Agosto 23</li>
            </ul>

            <!-- Septiembre -->
            <div class="month" onclick="toggleList('september')">Septiembre</div>
            <ul id="september" class="birthday-list">
                <li>Antonio Vargas - Septiembre 8</li>
            </ul>

            <!-- Octubre -->
            <div class="month" onclick="toggleList('october')">Octubre</div>
            <ul id="october" class="birthday-list">
                <li>Andrea Perez - Octubre 17</li>
            </ul>

            <!-- Noviembre -->
            <div class="month" onclick="toggleList('november')">Noviembre</div>
            <ul id="november" class="birthday-list">
                <li>Raul Herrera - Noviembre 3</li>
            </ul>

            <!-- Diciembre -->
            <div class="month" onclick="toggleList('december')">Diciembre</div>
            <ul id="december" class="birthday-list">
                <li>Monica Diaz - Diciembre 25</li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
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

.birthdays {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.month {
    background: #2c3e50;
    color: white;
    padding: 10px 20px;
    margin: 10px 0;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.month:hover {
    background: #e74c3c;
}

.birthday-list {
    list-style: none;
    padding: 0;
    display: none;
    background: white;
    border-radius: 5px;
    margin-top: 5px;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.birthday-list li {
    padding: 5px 0;
    border-bottom: 1px solid #ccc;
}

.birthday-list li:last-child {
    border-bottom: none;
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
    console.log('Birthday page loaded');

    const toggleList = (id) => {
        const list = document.getElementById(id);
        list.style.display = list.style.display === 'block' ? 'none' : 'block';
    };

    // Attach event listeners to each month div
    document.querySelectorAll('.month').forEach(month => {
        month.addEventListener('click', () => {
            toggleList(month.getAttribute('onclick').replace('toggleList(', '').replace(')', '').replace(/'/g, ''));
        });
    });
});
</script>
