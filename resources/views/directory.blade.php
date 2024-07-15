<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Ariel</title>
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
            <h1>Directorio de Ariel</h1>
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
        <section class="banner">
            <h2>Acceso rápido a la información de contacto de los empleados</h2>
        </section>
        <section class="filter-container">
            <label for="location-filter">Filtrar por ubicación:</label>
            <select id="location-filter">
                <option value="all">Todos</option>
                <option value="8825">8825</option>
                <option value="1920">1920</option>
                <option value="WC">WC</option>
                <option value="TW">TW</option>                 
                <option value="AZ">AZ</option>
                <option value="CA">CA</option>
                <option value="CAN">CAN</option>
                <option value="CN">CN</option>
                <option value="DE">DE</option>
                <option value="FL">FL</option>
                <option value="IL">IL</option>
                <option value="ME">ME</option>
                <option value="NJ">NJ</option>
                <option value="OH">OH</option>
                <option value="PA">PA</option>
                <option value="STL">STL</option>
            </select>
        </section>
        <section class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Departamento</th>
                        <th>Ubicación</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Extensión</th>
                        <th>Teléfono</th>
                        <th>Fax</th>
                        <th>E-mail</th>
                    </tr>
                    <tr>
                        <th><input type="text" id="search-department" placeholder="Buscar Departamento"></th>
                        <th><input type="text" id="search-location" placeholder="Buscar Ubicación"></th>
                        <th><input type="text" id="search-name" placeholder="Buscar Nombre"></th>
                        <th><input type="text" id="search-surname" placeholder="Buscar Apellido"></th>
                        <th><input type="text" id="search-extension" placeholder="Buscar Extensión"></th>
                        <th><input type="text" id="search-phone" placeholder="Buscar Teléfono"></th>
                        <th><input type="text" id="search-fax" placeholder="Buscar Fax"></th>
                        <th><input type="text" id="search-email" placeholder="Buscar E-mail"></th>
                    </tr>
                </thead>
                <tbody id="directory-table-body">
                    <tr data-location="8825">
                        <td>Accounting</td>
                        <td>8825</td>
                        <td>Xiaoye</td>
                        <td>Li</td>
                        <td>1240</td>
                        <td>(314) 787-2101</td>
                        <td>(314) 373-5861</td>
                        <td>xiaoyel@arielpremium.com</td>
                    </tr>
                    <tr data-location="STL">
                    <td>Accounting</td>
                    <td>STL</td>
                    <td>Maureen</td>
                    <td>Dickhens</td>
                    <td>1213</td>
                    <td></td>
                    <td></td>
                    <td>maureend@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Manager-Art</td>
                    <td>8825</td>
                    <td>Jackie</td>
                    <td>Dillard</td>
                    <td>1203</td>
                    <td></td>
                    <td></td>
                    <td>jackied@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Art</td>
                    <td>TW</td>
                    <td>Brenda</td>
                    <td>Chiu</td>
                    <td>1957</td>
                    <td></td>
                    <td></td>
                    <td>brendac@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Art</td>
                    <td>TW</td>
                    <td>Chloe</td>
                    <td>Chiu</td>
                    <td>1958</td>
                    <td></td>
                    <td></td>
                    <td>chloec@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Art</td>
                    <td>8825</td>
                    <td>Kimberly</td>
                    <td>O’Connor</td>
                    <td>1236</td>
                    <td></td>
                    <td></td>
                    <td>kimberlyo@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Art</td>
                    <td>8825</td>
                    <td>Jacenta</td>
                    <td>Anderson</td>
                    <td>1232</td>
                    <td></td>
                    <td></td>
                    <td>jacentaa@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Art</td>
                    <td>8825</td>
                    <td>Jennifer</td>
                    <td>Carroll</td>
                    <td>1238</td>
                    <td></td>
                    <td></td>
                    <td>jenniferc@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Art</td>
                    <td>8825</td>
                    <td>Jennifer</td>
                    <td>Morton</td>
                    <td>1251</td>
                    <td></td>
                    <td></td>
                    <td>jennifern@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Art</td>
                    <td>WC</td>
                    <td>Sandra</td>
                    <td>Guevara</td>
                    <td>3561</td>
                    <td></td>
                    <td></td>
                    <td>SandraG@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Art</td>
                    <td>WC</td>
                    <td>Magally</td>
                    <td>Alcocer</td>
                    <td>3541</td>
                    <td></td>
                    <td></td>
                    <td>magallya@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Art</td>
                    <td>WC</td>
                    <td>Reina</td>
                    <td>Burrola</td>
                    <td>3543</td>
                    <td></td>
                    <td></td>
                    <td>reinab@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Art</td>
                    <td>WC</td>
                    <td>Yasbeck</td>
                    <td>Cardenas</td>
                    <td>3527</td>
                    <td></td>
                    <td></td>
                    <td>YasbeckC@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Art</td>
                    <td>WC</td>
                    <td>Laura</td>
                    <td>Lopez</td>
                    <td>3539</td>
                    <td></td>
                    <td></td>
                    <td>LauraL@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Art</td>
                    <td>WC</td>
                    <td>Jose</td>
                    <td>Camacho</td>
                    <td>3542</td>
                    <td></td>
                    <td></td>
                    <td>JoseC@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>PROD Art</td>
                    <td>WC</td>
                    <td>Juan</td>
                    <td>Villeges</td>
                    <td>3544</td>
                    <td></td>
                    <td></td>
                    <td>juanv@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>PROD Art</td>
                    <td>WC</td>
                    <td>Jose</td>
                    <td>Martinez</td>
                    <td>3549</td>
                    <td></td>
                    <td></td>
                    <td>josem@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Art</td>
                    <td>TW</td>
                    <td>Miles</td>
                    <td>Lin</td>
                    <td>1958</td>
                    <td></td>
                    <td></td>
                    <td>milesl@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Art</td>
                    <td>8825</td>
                    <td>Susan</td>
                    <td>McCrea</td>
                    <td>1216</td>
                    <td></td>
                    <td></td>
                    <td>susanm@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>BSU</td>
                    <td>8825</td>
                    <td>Brian</td>
                    <td>Anderson</td>
                    <td>1257</td>
                    <td>(314) 787-2100</td>
                    <td></td>
                    <td>briananderson@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>BSU</td>
                    <td>8825</td>
                    <td>Carrie</td>
                    <td>Cheng</td>
                    <td>1318</td>
                    <td>(314) 787-2106</td>
                    <td>(314) 373-5845</td>
                    <td>carriec@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>BSU</td>
                    <td>8825</td>
                    <td>Fiona</td>
                    <td>Qu</td>
                    <td>1223</td>
                    <td></td>
                    <td></td>
                    <td>fionaq@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>BSU</td>
                    <td>WC</td>
                    <td>Joey</td>
                    <td>Velazquez</td>
                    <td>3518</td>
                    <td></td>
                    <td></td>
                    <td>joeyv@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>BSU</td>
                    <td>8825</td>
                    <td>Joon</td>
                    <td>Lee</td>
                    <td>1284</td>
                    <td>(314) 787-0722</td>
                    <td></td>
                    <td>joonl@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>BSU</td>
                    <td>8825</td>
                    <td>Michael</td>
                    <td>Trieu</td>
                    <td>1272</td>
                    <td></td>
                    <td></td>
                    <td>michaelt@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>BSU</td>
                    <td>WC</td>
                    <td>Raul</td>
                    <td>Bustamante</td>
                    <td>3525</td>
                    <td></td>
                    <td></td>
                    <td>raulb@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>BSU</td>
                    <td>TW</td>
                    <td>Rex</td>
                    <td>Wang</td>
                    <td>1958</td>
                    <td></td>
                    <td></td>
                    <td>rexw@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager-Credit</td>
                    <td>8825</td>
                    <td>Ling</td>
                    <td>Chi</td>
                    <td>1206</td>
                    <td>(314) 787-2116</td>
                    <td>(800) 806-1416</td>
                    <td>lingc@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Credit</td>
                    <td>8825</td>
                    <td>Cindy</td>
                    <td>Nhan</td>
                    <td>1204</td>
                    <td>(314) 787-0723</td>
                    <td>(866) 205-0435</td>
                    <td>cindyn@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Credit</td>
                    <td>TW</td>
                    <td>May</td>
                    <td>Hsu</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>mayh@arielpremium.com</td>
                </tr>
                <tr data-location="AZ">
                    <td>Credit</td>
                    <td>AZ</td>
                    <td>Queena</td>
                    <td>Chi-Ha</td>
                    <td>1354</td>
                    <td>(314) 787-0725</td>
                    <td>(800)792-3578</td>
                    <td>queenac@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Credit</td>
                    <td>TW</td>
                    <td>Sharon</td>
                    <td>Hung</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>sharonh@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Credit</td>
                    <td>8825</td>
                    <td>Sophie</td>
                    <td>Ma</td>
                    <td>1260</td>
                    <td>(314) 743-0631</td>
                    <td>(866) 760-0255</td>
                    <td>sophiem@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager-Distributor Service</td>
                    <td>8825</td>
                    <td>Tasha</td>
                    <td>Robertson</td>
                    <td>1274</td>
                    <td>(314) 787-0721</td>
                    <td>(800) 806-1347</td>
                    <td>tashar@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-Lead</td>
                    <td>WC</td>
                    <td>Carmen</td>
                    <td>Juarez</td>
                    <td>3523</td>
                    <td></td>
                    <td></td>
                    <td>carmenj@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-Lead</td>
                    <td>WC</td>
                    <td>Hector</td>
                    <td>Zapata</td>
                    <td>3522</td>
                    <td></td>
                    <td></td>
                    <td>hectorz@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP1 – Southeast & Foreign</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-2102</td>
                    <td>(888) 222-1395</td>
                    <td>asp1@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP1</td>
                    <td>WC</td>
                    <td>Angel</td>
                    <td>Hernandez</td>
                    <td>3536</td>
                    <td></td>
                    <td></td>
                    <td>angelh@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP1</td>
                    <td>WC</td>
                    <td>Vanessa</td>
                    <td>Martinez</td>
                    <td>3555</td>
                    <td></td>
                    <td></td>
                    <td>vanessam@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP2 – East</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-2198</td>
                    <td>(866) 207-7248</td>
                    <td>asp2@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP2</td>
                    <td>8825</td>
                    <td>Linda</td>
                    <td>Rohlfing</td>
                    <td>1237</td>
                    <td></td>
                    <td></td>
                    <td>lindar@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP2</td>
                    <td>WC</td>
                    <td>Luis</td>
                    <td>Alvarado</td>
                    <td>3569</td>
                    <td></td>
                    <td></td>
                    <td>luisa@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP3 – Midwest (N)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-0735</td>
                    <td>(866) 207-2358</td>
                    <td>asp3@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP3</td>
                    <td>8825</td>
                    <td>Scott</td>
                    <td>Plack</td>
                    <td>1299</td>
                    <td>(314) 787-2196</td>
                    <td>(800) 806-0744</td>
                    <td>scottp@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP3</td>
                    <td>8825</td>
                    <td>Brandy</td>
                    <td>Laster</td>
                    <td>1218</td>
                    <td>(314) 787-0731</td>
                    <td>(800) 799-2634</td>
                    <td>brandyl@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP3</td>
                    <td>WC</td>
                    <td>Stephany</td>
                    <td>Ramirez</td>
                    <td>3524</td>
                    <td></td>
                    <td></td>
                    <td>stephanyr@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP4 – National</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-2111</td>
                    <td>(800) 798-0795</td>
                    <td>asp4@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP4</td>
                    <td>8825</td>
                    <td>Scott</td>
                    <td>Plack</td>
                    <td>1299</td>
                    <td>(314) 787-2196</td>
                    <td>(800) 806-0744</td>
                    <td>scottp@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP4</td>
                    <td>8825</td>
                    <td>Jean</td>
                    <td>Callahan</td>
                    <td>1254</td>
                    <td></td>
                    <td></td>
                    <td>jeanc@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP4</td>
                    <td>WC</td>
                    <td>Miguel</td>
                    <td>Partida</td>
                    <td>3515</td>
                    <td></td>
                    <td></td>
                    <td>miguelp@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP5 – South</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 743-3580</td>
                    <td>(866) 760-0256</td>
                    <td>asp5@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP5</td>
                    <td>8825</td>
                    <td>Nancy</td>
                    <td>Cranmer</td>
                    <td>1276</td>
                    <td></td>
                    <td></td>
                    <td>nancyc@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP5</td>
                    <td>WC</td>
                    <td>Omar</td>
                    <td>Monreal</td>
                    <td>3568</td>
                    <td></td>
                    <td></td>
                    <td>omarm@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP6 – Southwest</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-2109</td>
                    <td>(800) 792-3794</td>
                    <td>asp6@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP6</td>
                    <td>WC</td>
                    <td>Samantha</td>
                    <td>Tiscareno</td>
                    <td>3570</td>
                    <td></td>
                    <td></td>
                    <td>samanthat@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-ASP6</td>
                    <td>WC</td>
                    <td>Alejandrina</td>
                    <td>Zamora</td>
                    <td>3517</td>
                    <td></td>
                    <td></td>
                    <td>alejandrinaz@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP6</td>
                    <td>STL</td>
                    <td>Amber</td>
                    <td>Howard</td>
                    <td>1249</td>
                    <td></td>
                    <td></td>
                    <td>amberh@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP7 – Northwest</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-2118</td>
                    <td>(866) 760-0253</td>
                    <td>asp7@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP7</td>
                    <td>8825</td>
                    <td>Brandy</td>
                    <td>Laster</td>
                    <td>1218</td>
                    <td>(314) 787-0731</td>
                    <td>(800) 799-2634</td>
                    <td>brandyl@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-ASP7</td>
                    <td>TW</td>
                    <td>Alex</td>
                    <td>Hsu</td>
                    <td>1961</td>
                    <td></td>
                    <td></td>
                    <td>alexh@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP8 – Midwest (E)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 743-3588</td>
                    <td>(866) 207-5615</td>
                    <td>asp8@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP8</td>
                    <td>8825</td>
                    <td>Renee</td>
                    <td>Beile</td>
                    <td>1263</td>
                    <td></td>
                    <td></td>
                    <td>reneeb@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP8</td>
                    <td>8825</td>
                    <td>Sheena</td>
                    <td>Berry</td>
                    <td>1273</td>
                    <td></td>
                    <td></td>
                    <td>sheenab@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP9 – New England</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-0736</td>
                    <td>(800) 792-3586</td>
                    <td>asp9@arielpremium.com</td>
                </tr>
                <tr data-location="CA">
                    <td>DS-ASP9</td>
                    <td>CA</td>
                    <td>Corrin</td>
                    <td>Francisco</td>
                    <td>1932</td>
                    <td></td>
                    <td></td>
                    <td>corrinf@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP9</td>
                    <td>8825</td>
                    <td>Missy</td>
                    <td>Mason</td>
                    <td>1253</td>
                    <td></td>
                    <td></td>
                    <td>missym@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP10 – Northeast</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-0740</td>
                    <td>(800) 840-4398</td>
                    <td>asp10@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP10</td>
                    <td>8825</td>
                    <td>Linda</td>
                    <td>Rohlfing</td>
                    <td>1237</td>
                    <td></td>
                    <td></td>
                    <td>lindar@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP10</td>
                    <td>8825</td>
                    <td>Ashley</td>
                    <td>Martin</td>
                    <td>1293</td>
                    <td>(314) 743-3585</td>
                    <td>(866) 207-5614</td>
                    <td>ashleym@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP11 – Midwest (S)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-0728</td>
                    <td>(800) 792-3584</td>
                    <td>asp11@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP11</td>
                    <td>8825</td>
                    <td>Sheena</td>
                    <td>Berry</td>
                    <td>1273</td>
                    <td></td>
                    <td></td>
                    <td>sheenab@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-ASP11</td>
                    <td>TW</td>
                    <td>Deborah</td>
                    <td>Lo</td>
                    <td>1962</td>
                    <td></td>
                    <td></td>
                    <td>deborahl@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-ASP12 – Mid-Atlantic</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-2112</td>
                    <td>(800) 806-1353</td>
                    <td>asp12@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-ASP12</td>
                    <td>8825</td>
                    <td>Angela</td>
                    <td>Adams</td>
                    <td>1233</td>
                    <td></td>
                    <td></td>
                    <td>angelaa@arielpremium.com</td>
                </tr>
                <tr data-location="CA">
                    <td>DS-ASP12</td>
                    <td>CA</td>
                    <td>Corrin</td>
                    <td>Francisco</td>
                    <td>1932</td>
                    <td></td>
                    <td></td>
                    <td>corrinf@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-ASP12</td>
                    <td>TW</td>
                    <td>Hoya</td>
                    <td>Wang</td>
                    <td>1239</td>
                    <td></td>
                    <td></td>
                    <td>hoyaw@arielpremium.com</td>
                </tr>
                <tr>
                    <td>DS-Canada</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>(314) 787-0738</td>
                    <td>(866) 760-0248</td>
                    <td>canada@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-Canada</td>
                    <td>8825</td>
                    <td>Colleen</td>
                    <td>Wadley</td>
                    <td>1261</td>
                    <td>(314) 787-2115</td>
                    <td>(866) 760-0250</td>
                    <td>colleenw@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-Canada</td>
                    <td>8825</td>
                    <td>Brooke</td>
                    <td>Royer</td>
                    <td>1271</td>
                    <td></td>
                    <td></td>
                    <td>brooker@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-Canada</td>
                    <td>TW</td>
                    <td>Kiki</td>
                    <td>Liao</td>
                    <td>1214</td>
                    <td></td>
                    <td></td>
                    <td>kikil@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-Claims</td>
                    <td>8825</td>
                    <td>Becky</td>
                    <td>Elledge</td>
                    <td>1207</td>
                    <td></td>
                    <td></td>
                    <td>beckye@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>DS-Production Coordinator</td>
                    <td>1920</td>
                    <td>Leigh</td>
                    <td>Goodman</td>
                    <td>1285</td>
                    <td>(314) 743-3595</td>
                    <td></td>
                    <td>leighg@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-Production Coordinator</td>
                    <td>8825</td>
                    <td>David</td>
                    <td>Wallace</td>
                    <td>1221</td>
                    <td></td>
                    <td></td>
                    <td>davidw@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-Inventory Specialist</td>
                    <td>8825</td>
                    <td>Elizabeth</td>
                    <td>Tiemann</td>
                    <td>1292</td>
                    <td>(314) 743-3581</td>
                    <td>(866) 760-0249</td>
                    <td>elizabetht@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-CSR</td>
                    <td>WC</td>
                    <td>Luis</td>
                    <td>Alvarado</td>
                    <td>3569</td>
                    <td></td>
                    <td></td>
                    <td>luisa@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-CSR</td>
                    <td>WC</td>
                    <td>Sotero</td>
                    <td>Cruz</td>
                    <td>3526</td>
                    <td></td>
                    <td></td>
                    <td>soteroc@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-CSR</td>
                    <td>WC</td>
                    <td>Edgar</td>
                    <td>Herrera</td>
                    <td>3547</td>
                    <td></td>
                    <td></td>
                    <td>edgarh@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-CSR</td>
                    <td>TW</td>
                    <td>Hsuan</td>
                    <td>Lu</td>
                    <td>1957</td>
                    <td></td>
                    <td></td>
                    <td>hsuanl@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-CSR</td>
                    <td>TW</td>
                    <td>Irene</td>
                    <td>Lu</td>
                    <td>1959</td>
                    <td></td>
                    <td></td>
                    <td>irenel@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-CSR</td>
                    <td>TW</td>
                    <td>James</td>
                    <td>Su</td>
                    <td>1957</td>
                    <td></td>
                    <td></td>
                    <td>jamess@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-CSR</td>
                    <td>TW</td>
                    <td>Jennifer</td>
                    <td>Lu</td>
                    <td>1960</td>
                    <td></td>
                    <td></td>
                    <td>jenniferl@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-CSR</td>
                    <td>TW</td>
                    <td>Ling</td>
                    <td>Peng</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>lingp@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-CSR</td>
                    <td>TW</td>
                    <td>Phoebe</td>
                    <td>Tsai</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>phoebet@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>DS-CSR</td>
                    <td>TW</td>
                    <td>Sunny</td>
                    <td>Yi</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>sunnyyi@arielpremium.com</td>
                </tr>
                <tr data-location="CN">
                    <td>DS-OS</td>
                    <td>CN</td>
                    <td>Alphi</td>
                    <td>Lin</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>alphil@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>DS-OS</td>
                    <td>WC</td>
                    <td>Mayra</td>
                    <td>Ambriz</td>
                    <td>3510</td>
                    <td></td>
                    <td></td>
                    <td>mayraa@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS-OS</td>
                    <td>8825</td>
                    <td>Jessica</td>
                    <td>Bolling</td>
                    <td>1209</td>
                    <td>(314) 787-0727</td>
                    <td>(800)792-3580</td>
                    <td>jessicab@arielpremium.com</td>
                </tr>
                <tr data-location="NJ">
                    <td>DS-OS</td>
                    <td>NJ</td>
                    <td>Susan</td>
                    <td>Giffear</td>
                    <td>1933</td>
                    <td></td>
                    <td></td>
                    <td>susang@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS- Credit</td>
                    <td>8825</td>
                    <td>Zoey</td>
                    <td>Laster</td>
                    <td>1227</td>
                    <td></td>
                    <td></td>
                    <td>zoeyl@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>DS- Credit</td>
                    <td>8825</td>
                    <td>Cynthia</td>
                    <td>Chen</td>
                    <td>1245</td>
                    <td></td>
                    <td></td>
                    <td>cynthiac@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>General Office</td>
                    <td>8825</td>
                    <td>May</td>
                    <td>Lau-Kahle</td>
                    <td>1255</td>
                    <td></td>
                    <td></td>
                    <td>mayl@arielpremium.com</td>
                </tr>
                <tr>
                    <td>General Office</td>
                    <td>8825</td>
                    <td>Conference Room</td>
                    <td>Partnership</td>
                    <td>1300</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>General Office</td>
                    <td>1920</td>
                    <td>Conference Room</td>
                    <td></td>
                    <td>1330</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr data-location="WC">
                    <td>General Office-Import/Export</td>
                    <td>WC</td>
                    <td>Angel</td>
                    <td>Villa</td>
                    <td>3560</td>
                    <td></td>
                    <td></td>
                    <td>angelv@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>General Office-Import/Export</td>
                    <td>WC</td>
                    <td>Genaro</td>
                    <td>Rodriguez</td>
                    <td>3557</td>
                    <td></td>
                    <td></td>
                    <td>genaror@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>General Office-Purchasing</td>
                    <td>WC</td>
                    <td>Abigail</td>
                    <td>Sanchez</td>
                    <td>3558</td>
                    <td></td>
                    <td></td>
                    <td>abigails@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager-Human Resources</td>
                    <td>8825</td>
                    <td>Kathryn</td>
                    <td>Tung</td>
                    <td>1246</td>
                    <td>(314) 787-2105</td>
                    <td></td>
                    <td>kathrynt@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>HR</td>
                    <td>WC</td>
                    <td>Alejandra</td>
                    <td>Contreras</td>
                    <td>3553</td>
                    <td></td>
                    <td></td>
                    <td>alejandrac@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>HR</td>
                    <td>WC</td>
                    <td>Alfredo</td>
                    <td>Morales</td>
                    <td>3513</td>
                    <td></td>
                    <td></td>
                    <td>alfredom@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>HR</td>
                    <td>8825</td>
                    <td>Becky</td>
                    <td>Shaw</td>
                    <td>1256</td>
                    <td></td>
                    <td></td>
                    <td>beckys@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>HR</td>
                    <td>8825</td>
                    <td>Lydia</td>
                    <td>Sun</td>
                    <td>1314</td>
                    <td></td>
                    <td></td>
                    <td>lydias@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>HR</td>
                    <td>8825</td>
                    <td>Jennifer</td>
                    <td>Tran</td>
                    <td>1282</td>
                    <td></td>
                    <td></td>
                    <td>jennifert@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>HR</td>
                    <td>8825</td>
                    <td>Li</td>
                    <td>Fang</td>
                    <td>1275</td>
                    <td></td>
                    <td></td>
                    <td>lif@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>HR</td>
                    <td>8825</td>
                    <td>Sophia</td>
                    <td>Lin</td>
                    <td>1269</td>
                    <td></td>
                    <td></td>
                    <td>sophial@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>HR</td>
                    <td>8825</td>
                    <td>Tina</td>
                    <td>Tang</td>
                    <td>1220</td>
                    <td></td>
                    <td></td>
                    <td>tinat@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager</td>
                    <td>8825</td>
                    <td>Kan</td>
                    <td>Hsu</td>
                    <td>1212</td>
                    <td>(314) 787-0730</td>
                    <td>(866)205-1112</td>
                    <td>kanhsu@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager</td>
                    <td>8825</td>
                    <td>Tai</td>
                    <td>Lin</td>
                    <td>1250</td>
                    <td>(314) 787-2107</td>
                    <td></td>
                    <td>tai@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager</td>
                    <td>8825</td>
                    <td>Yuh-Ling</td>
                    <td>Lu</td>
                    <td>1215</td>
                    <td>(314) 787-0733</td>
                    <td>(866)205-6106</td>
                    <td>yuhling@arielpremium.com</td>
                </tr>
                <tr data-location="CN">
                    <td>Manager-China</td>
                    <td>CN</td>
                    <td>Liping</td>
                    <td>Zhang</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>lipingz@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Manager-Taiwan</td>
                    <td>TW</td>
                    <td>Nancy</td>
                    <td>Wang</td>
                    <td>1958</td>
                    <td></td>
                    <td></td>
                    <td>nancyw@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Manager-West Coast</td>
                    <td>WC</td>
                    <td>Jorge</td>
                    <td>Reyna</td>
                    <td>3530</td>
                    <td>(858) 997-6452</td>
                    <td></td>
                    <td>jorger@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager-Marketing</td>
                    <td>8825</td>
                    <td>Caitlin</td>
                    <td>Downing</td>
                    <td>1289</td>
                    <td></td>
                    <td></td>
                    <td>caitlind@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Marketing</td>
                    <td>8825</td>
                    <td>Adam</td>
                    <td>Bearden</td>
                    <td>1202</td>
                    <td></td>
                    <td></td>
                    <td>adamb@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Marketing</td>
                    <td>WC</td>
                    <td>Guadalupe</td>
                    <td>Herrera</td>
                    <td>3566</td>
                    <td></td>
                    <td></td>
                    <td>guadalupeh@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Marketing</td>
                    <td>WC</td>
                    <td>Patricia</td>
                    <td>Amador</td>
                    <td>3565</td>
                    <td></td>
                    <td></td>
                    <td>patriciaa@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Marketing</td>
                    <td>WC</td>
                    <td>Liha</td>
                    <td>Macias</td>
                    <td>3546</td>
                    <td></td>
                    <td></td>
                    <td>liham@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Marketing</td>
                    <td>WC</td>
                    <td>Mayra</td>
                    <td>Moran</td>
                    <td>3529</td>
                    <td></td>
                    <td></td>
                    <td>mayram@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Marketing</td>
                    <td>WC</td>
                    <td>Pricilla</td>
                    <td>Castillo</td>
                    <td>3563</td>
                    <td></td>
                    <td></td>
                    <td>pricillac@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Marketing</td>
                    <td>8825</td>
                    <td>Lichun</td>
                    <td>Ma</td>
                    <td>1286</td>
                    <td></td>
                    <td></td>
                    <td>lichunm@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Marketing</td>
                    <td>8825</td>
                    <td>Jay</td>
                    <td>Chi</td>
                    <td>1259</td>
                    <td></td>
                    <td></td>
                    <td>jaychi@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Marketing</td>
                    <td>8825</td>
                    <td>Joan</td>
                    <td>Morgan</td>
                    <td>1229</td>
                    <td>(314) 787-0739</td>
                    <td></td>
                    <td>joanm@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Marketing</td>
                    <td>8825</td>
                    <td>Kate</td>
                    <td>Lam</td>
                    <td>1268</td>
                    <td></td>
                    <td></td>
                    <td>katel@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Marketing</td>
                    <td>8825</td>
                    <td>Sara</td>
                    <td>Thompson</td>
                    <td>1270</td>
                    <td></td>
                    <td></td>
                    <td>sarat@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Marketing</td>
                    <td>8825</td>
                    <td>Xinya</td>
                    <td>Huang</td>
                    <td>1242</td>
                    <td></td>
                    <td></td>
                    <td>xinyah@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager-Production</td>
                    <td>8825</td>
                    <td>HongYu</td>
                    <td>Zhang</td>
                    <td>1222</td>
                    <td></td>
                    <td></td>
                    <td>hongyuz@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>8825 Digital Print</td>
                    <td>Phone</td>
                    <td>1312</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Production</td>
                    <td>8825</td>
                    <td>8825 Label Print</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>prodlabel@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>8825 Packaging</td>
                    <td>Phone</td>
                    <td>1326</td>
                    <td></td>
                    <td></td>
                    <td>packaging8825@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>8825 Unpack</td>
                    <td>Phone</td>
                    <td>1228</td>
                    <td></td>
                    <td></td>
                    <td>unpack8825@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>Daniel</td>
                    <td>Tsai</td>
                    <td>1225</td>
                    <td></td>
                    <td></td>
                    <td>prodmaint@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>Meimei</td>
                    <td>Wang</td>
                    <td>1258</td>
                    <td></td>
                    <td></td>
                    <td>imprint@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>Michael</td>
                    <td>Wang</td>
                    <td>1313</td>
                    <td></td>
                    <td></td>
                    <td>michaelw@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>Nicole</td>
                    <td>Zhang</td>
                    <td>1311</td>
                    <td></td>
                    <td></td>
                    <td>nicolez@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Production</td>
                    <td>8825</td>
                    <td>Paula</td>
                    <td>Reece</td>
                    <td>1265</td>
                    <td></td>
                    <td></td>
                    <td>paular@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Production</td>
                    <td>1920</td>
                    <td>Belinda</td>
                    <td>Wang</td>
                    <td>1361</td>
                    <td></td>
                    <td></td>
                    <td>belindaw@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Production</td>
                    <td>1920</td>
                    <td>Screen</td>
                    <td>Printing</td>
                    <td>1360</td>
                    <td></td>
                    <td></td>
                    <td>production1920@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Production</td>
                    <td>1920</td>
                    <td>Henry</td>
                    <td>Chen</td>
                    <td>1224</td>
                    <td></td>
                    <td></td>
                    <td>henryc@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Production</td>
                    <td>1920</td>
                    <td>1920 Production</td>
                    <td>Imprinting</td>
                    <td>1366</td>
                    <td></td>
                    <td></td>
                    <td>jingw@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Production</td>
                    <td>1920</td>
                    <td>Sam</td>
                    <td>Chen</td>
                    <td>1363</td>
                    <td></td>
                    <td></td>
                    <td>digitalprint1920@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Production</td>
                    <td>1920</td>
                    <td>Yihua</td>
                    <td>Jiang</td>
                    <td>1280</td>
                    <td></td>
                    <td></td>
                    <td>yihuaj@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Production</td>
                    <td>1920</td>
                    <td>Jing</td>
                    <td>Wang</td>
                    <td>1320</td>
                    <td></td>
                    <td></td>
                    <td>jingw@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production</td>
                    <td>WC</td>
                    <td>Ottmar</td>
                    <td>Angulo</td>
                    <td>3511</td>
                    <td></td>
                    <td></td>
                    <td>ottmara@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production</td>
                    <td>WC</td>
                    <td>Isela</td>
                    <td>Ramirez</td>
                    <td>3519</td>
                    <td></td>
                    <td></td>
                    <td>iselar@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production</td>
                    <td>WC</td>
                    <td>West Coast</td>
                    <td>Maintenance</td>
                    <td>3537</td>
                    <td></td>
                    <td></td>
                    <td>maintenancewc@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production</td>
                    <td>WC</td>
                    <td>Norberto</td>
                    <td>Alvarado</td>
                    <td>3550</td>
                    <td></td>
                    <td></td>
                    <td>norbertoa@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production</td>
                    <td>WC</td>
                    <td>Karen</td>
                    <td>Martinez</td>
                    <td>3521</td>
                    <td></td>
                    <td></td>
                    <td>karenm@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Producton QC</td>
                    <td>WC</td>
                    <td>Janette</td>
                    <td>Serrato</td>
                    <td>3401</td>
                    <td></td>
                    <td></td>
                    <td>janettes@arielpremim.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production-Laser Plate Room</td>
                    <td>WC</td>
                    <td>LaserPlate</td>
                    <td>Room D</td>
                    <td>3533</td>
                    <td></td>
                    <td></td>
                    <td>LaserPlateWC@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production-Digital Print</td>
                    <td>WC</td>
                    <td>BottleJet</td>
                    <td>Room</td>
                    <td>3532</td>
                    <td></td>
                    <td></td>
                    <td>digitalprintwc@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Production-Digital Print</td>
                    <td>WC</td>
                    <td>Screen</td>
                    <td>i-Image STE</td>
                    <td>3552</td>
                    <td></td>
                    <td></td>
                    <td>digitalprintwc@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Producton-Embroidery</td>
                    <td>WC</td>
                    <td>Embroidery</td>
                    <td>Room</td>
                    <td>3572</td>
                    <td></td>
                    <td></td>
                    <td>embroiderywc@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager-Products</td>
                    <td>8825</td>
                    <td>Sharon</td>
                    <td>Lin</td>
                    <td>1234</td>
                    <td>(314) 787-0737</td>
                    <td>(800)801-1018</td>
                    <td>sharonl@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Products</td>
                    <td>8825</td>
                    <td>Baozhen</td>
                    <td>Ma</td>
                    <td>1247</td>
                    <td></td>
                    <td></td>
                    <td>baozhenm@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Products</td>
                    <td>TW</td>
                    <td>Emily</td>
                    <td>Wu</td>
                    <td>1958</td>
                    <td></td>
                    <td></td>
                    <td>emilyw@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Products</td>
                    <td>8825</td>
                    <td>Erin</td>
                    <td>Wang</td>
                    <td>1264</td>
                    <td></td>
                    <td></td>
                    <td>erinw@arielpremium.com</td>
                </tr>
                <tr data-location="CN">
                    <td>Products</td>
                    <td>CN</td>
                    <td>Joe</td>
                    <td>Hong</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>joeh@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Products</td>
                    <td>8825</td>
                    <td>Li</td>
                    <td>Pei</td>
                    <td>1266</td>
                    <td></td>
                    <td></td>
                    <td>lip@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Products</td>
                    <td>8825</td>
                    <td>May</td>
                    <td>Lee</td>
                    <td>1241</td>
                    <td></td>
                    <td></td>
                    <td>maylee@arielpremium.com</td>
                </tr>
                <tr data-location="CN">
                    <td>Products</td>
                    <td>CN</td>
                    <td>Michael</td>
                    <td>Yang</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>michaely@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Products</td>
                    <td>8825</td>
                    <td>Niki</td>
                    <td>Wu</td>
                    <td>1248</td>
                    <td>(314) 787-2199</td>
                    <td></td>
                    <td>nikiw@arielpremium.com</td>
                </tr>
                <tr data-location="TW">
                    <td>Products</td>
                    <td>TW</td>
                    <td>Olivia</td>
                    <td>Lee</td>
                    <td>1957</td>
                    <td></td>
                    <td></td>
                    <td>olivial@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Products</td>
                    <td>WC</td>
                    <td>Rafael</td>
                    <td>Sanchez</td>
                    <td>3516</td>
                    <td></td>
                    <td></td>
                    <td>rafaels@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Products</td>
                    <td>8825</td>
                    <td>Shengmei</td>
                    <td>Chen</td>
                    <td>1362</td>
                    <td></td>
                    <td></td>
                    <td>shengmeic@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Products</td>
                    <td>8825</td>
                    <td>Zongying</td>
                    <td>He</td>
                    <td>1210</td>
                    <td></td>
                    <td></td>
                    <td>zongyingh@arielpremium.com</td>
                </tr>
                <tr>
                    <td>Products</td>
                    <td>US</td>
                    <td>Rudy</td>
                    <td>Woodard</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>rudyw@arielpremium.com</td>
                </tr>
                <tr data-location="PA">
                    <td>Manager-Sales</td>
                    <td>PA</td>
                    <td>Rich</td>
                    <td>Harbert</td>
                    <td>1244</td>
                    <td></td>
                    <td>(267) 614-1391</td>
                    <td>richh@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Sales</td>
                    <td>8825</td>
                    <td>Amy</td>
                    <td>Kitzman</td>
                    <td>1278</td>
                    <td>(314) 787-0726</td>
                    <td>(314) 707-4644</td>
                    <td>amyk@arielpremium.com</td>
                </tr>
                <tr data-location="PA">
                    <td>Sales</td>
                    <td>PA</td>
                    <td>Brendan</td>
                    <td>Pigott</td>
                    <td></td>
                    <td></td>
                    <td>(215) 936-9774</td>
                    <td>brendanp@arielpremium.com</td>
                </tr>
                <tr data-location="OH">
                    <td>Sales</td>
                    <td>OH</td>
                    <td>Dan</td>
                    <td>Alspaugh</td>
                    <td></td>
                    <td></td>
                    <td>(614) 284-5013</td>
                    <td>dan@arielpremium.com</td>
                </tr>
                <tr data-location="ME">
                    <td>Sales</td>
                    <td>ME</td>
                    <td>Darrell</td>
                    <td>Fronczek</td>
                    <td></td>
                    <td></td>
                    <td>(617) 780-5884</td>
                    <td>darrellf@arielpremium.com</td>
                </tr>
                <tr data-location="IL">
                    <td>Sales</td>
                    <td>IL</td>
                    <td>Jeff</td>
                    <td>Reed</td>
                    <td></td>
                    <td></td>
                    <td>(630) 258-6853</td>
                    <td>jeffr@arielpremium.com</td>
                </tr>
                <tr data-location="CA">
                    <td>Sales</td>
                    <td>CA</td>
                    <td>Larry</td>
                    <td>Baida</td>
                    <td></td>
                    <td></td>
                    <td>(951) 505-1813</td>
                    <td>larryb@arielpremium.com</td>
                </tr>
                <tr data-location="CA">
                    <td>Sales</td>
                    <td>CA</td>
                    <td>Laura</td>
                    <td>Brewer</td>
                    <td></td>
                    <td></td>
                    <td>(510) 289-9979</td>
                    <td>laurab@arielpremium.com</td>
                </tr>
                <tr data-location="CA">
                    <td>Sales</td>
                    <td>CA</td>
                    <td>Lindsey</td>
                    <td>Goodwin</td>
                    <td>1235</td>
                    <td></td>
                    <td>(415) 902-8956</td>
                    <td>lindseyg@arielpremium.com</td>
                </tr>
                <tr data-location="FL">
                    <td>Sales</td>
                    <td>FL</td>
                    <td>Martin</td>
                    <td>Guthrie</td>
                    <td></td>
                    <td></td>
                    <td>(407) 580-8816</td>
                    <td>martyg@arielpremium.com</td>
                </tr>
                <tr data-location="CAN">
                    <td>Sales</td>
                    <td>CAN</td>
                    <td>Neil</td>
                    <td>Mihan</td>
                    <td></td>
                    <td></td>
                    <td>(905) 761-7023</td>
                    <td>neil@prg.ca</td>
                </tr>
                <tr data-location="DE">
                    <td>Sales</td>
                    <td>DE</td>
                    <td>Tony</td>
                    <td>Limetti</td>
                    <td></td>
                    <td></td>
                    <td>(732) 241-9638</td>
                    <td>tonyl@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Manager-Shipping/Warehouse</td>
                    <td>8825</td>
                    <td>Bill</td>
                    <td>Brueggen</td>
                    <td>1211</td>
                    <td>(314) 787-0729</td>
                    <td></td>
                    <td>williamb@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Shipping-Supervisor</td>
                    <td>8825</td>
                    <td>Ross</td>
                    <td>Newmann</td>
                    <td>1205</td>
                    <td></td>
                    <td></td>
                    <td>rossn@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Shipping-Samples</td>
                    <td>8825</td>
                    <td>David</td>
                    <td>Noll</td>
                    <td>1243</td>
                    <td></td>
                    <td></td>
                    <td>davidn@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Shipping</td>
                    <td>1920</td>
                    <td>1920</td>
                    <td>Shipping</td>
                    <td>1364</td>
                    <td></td>
                    <td></td>
                    <td>shipping@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Shipping D</td>
                    <td>WC</td>
                    <td>West Coast</td>
                    <td>Shipping</td>
                    <td>3528</td>
                    <td></td>
                    <td></td>
                    <td>shipping@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Shipping E</td>
                    <td>WC</td>
                    <td>West Coast</td>
                    <td>Shipping</td>
                    <td>3545</td>
                    <td></td>
                    <td></td>
                    <td>shippingwce@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Warehouse-Supervisor</td>
                    <td>8825</td>
                    <td>Jake</td>
                    <td>Kelly</td>
                    <td>1262</td>
                    <td></td>
                    <td></td>
                    <td>jakek@arielpremium.com</td>
                </tr>
                <tr data-location="8825">
                    <td>Warehouse</td>
                    <td>8825</td>
                    <td>8825 Warehouse</td>
                    <td>Dock</td>
                    <td>1328</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr data-location="8825">
                    <td>Warehouse</td>
                    <td>8825</td>
                    <td>8825 Warehouse</td>
                    <td>Office</td>
                    <td>1217</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr data-location="8825">
                    <td>Warehouse</td>
                    <td>8825</td>
                    <td>Chris</td>
                    <td>Baylor</td>
                    <td>1219</td>
                    <td></td>
                    <td></td>
                    <td>chrisb@arielpremium.com</td>
                </tr>
                <tr data-location="1920">
                    <td>Warehouse</td>
                    <td>1920</td>
                    <td>1920 Warehouse</td>
                    <td>Phone</td>
                    <td>1365</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr data-location="WC">
                    <td>Warehouse</td>
                    <td>WC</td>
                    <td>Marco</td>
                    <td>Llanes</td>
                    <td>3535</td>
                    <td></td>
                    <td></td>
                    <td>marcol@arielpremium.com</td>
                </tr>
                <tr data-location="WC">
                    <td>Warehouse</td>
                    <td>WC</td>
                    <td>Jose Luis</td>
                    <td>Ponce</td>
                    <td>3538</td>
                    <td></td>
                    <td></td>
                    <td>joseluisp@arielpremium.com</td>
                </tr>
                    <!-- Más filas aquí -->
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
    </footer>
    <script src="{{ asset('js/intranet.js') }}" defer></script>
</body>
</html>
<style>
body {
    font-family: 'Roboto', sans-serif;
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

.banner {
    text-align: center;
    background-color: #ecf0f1;
    padding: 2rem;
    border-radius: 8px;
    margin: 2rem;
}

.table-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
    height: 80vh; /* Adjust as needed */
    overflow-y: auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    text-align: left;
    padding: 12px;
}

th {
    background-color: #2c3e50;
    color: #ecf0f1;
    position: sticky;
    top: 0;
    z-index: 1;
}

th input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border: none;
    border-radius: 4px;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

footer {
    background-color: #2c3e50;
    color: #ecf0f1;
    text-align: center;
    padding: 1rem 0;
}

.filter-container {
    margin: 1rem;
    text-align: center;
}

.filter-container label {
    font-weight: bold;
    margin-right: 0.5rem;
}

.filter-container select {
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s;
}

.filter-container select:focus {
    border-color: #2c3e50;
    outline: none;
}

.filter-container select option {
    padding: 0.5rem;
}

.filter-container select:hover {
    cursor: pointer;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationFilter = document.getElementById('location-filter');
    const tableBody = document.getElementById('directory-table-body');

    locationFilter.addEventListener('change', function () {
        const selectedLocation = this.value;
        filterTableByLocation(selectedLocation);
    });

    function filterTableByLocation(location) {
        const rows = tableBody.getElementsByTagName('tr');
        for (let row of rows) {
            const rowLocation = row.getAttribute('data-location');
            if (location === 'all' || rowLocation === location) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    const inputs = document.querySelectorAll('thead input');
    inputs.forEach(input => {
        input.addEventListener('keyup', function () {
            const column = this.id.split('-')[1];
            const value = this.value.toLowerCase();
            filterTableByColumn(column, value);
        });
    });

    function filterTableByColumn(column, value) {
        const rows = tableBody.getElementsByTagName('tr');
        for (let row of rows) {
            const cell = row.querySelector(`td:nth-child(${getColumnIndex(column)})`);
            if (cell) {
                if (cell.textContent.toLowerCase().includes(value)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    }

    function getColumnIndex(column) {
        switch (column) {
            case 'department': return 1;
            case 'location': return 2;
            case 'name': return 3;
            case 'surname': return 4;
            case 'extension': return 5;
            case 'phone': return 6;
            case 'fax': return 7;
            case 'email': return 8;
            default: return 1;
        }
    }
});
</script>   