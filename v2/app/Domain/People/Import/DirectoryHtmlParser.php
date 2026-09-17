<?php

namespace App\Domain\People\Import;

use DOMDocument;
use DOMElement;
use DOMXPath;

/**
 * Lee los ~200 empleados que la v1 tiene escritos a mano dentro de
 * resources/views/directory.blade.php (2,304 líneas de HTML).
 *
 * Usa DOM y no expresiones regulares a propósito: el HTML de la v1 tiene
 * indentación inconsistente, celdas vacías, y apóstrofes tipográficos
 * (O’Connor). Un regex sobre eso falla en silencio, que es la peor forma
 * de fallar en una migración de datos.
 *
 * El parser NO decide ni corrige nada: recoge lo que hay y marca lo que no
 * cuadra. Decidir es trabajo de RH (tarea 2.5 del BUILD_PLAN).
 */
class DirectoryHtmlParser
{
    /** Orden de las columnas en la tabla de la v1. */
    private const COLUMNAS = [
        0 => 'department',
        1 => 'location_cell',
        2 => 'first_name',
        3 => 'last_name',
        4 => 'extension',
        5 => 'phone',
        6 => 'fax',
        7 => 'email',
    ];

    /**
     * @return array{filas: array<int, array<string, mixed>>, avisos: array<int, string>}
     */
    public function parse(string $rutaHtml): array
    {
        if (! is_file($rutaHtml)) {
            throw new \RuntimeException("No encontré el archivo: {$rutaHtml}");
        }

        $html = file_get_contents($rutaHtml);

        // El archivo es una plantilla Blade. Quitamos las interpolaciones
        // {{ ... }}, que sí ensucian el texto de las celdas.
        //
        // NO quitamos las directivas @algo: un regex como /@\w+/ se come la
        // arroba de los correos y convierte xiaoyel@arielpremium.com en
        // xiaoyel.com. Nos costó un falso "192 correos inválidos" al primer
        // intento. Además no hace falta: dentro de <tbody> solo hay <tr><td>
        // con texto plano, ninguna directiva.
        $html = preg_replace('/\{\{.*?\}\}/s', '', $html);

        $doc = new DOMDocument();
        $previo = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previo);

        $xpath = new DOMXPath($doc);
        $tbody = $xpath->query('//tbody[@id="directory-table-body"]')->item(0);

        if (! $tbody) {
            throw new \RuntimeException('No encontré <tbody id="directory-table-body"> en el HTML.');
        }

        $filas = [];
        $avisos = [];
        $numero = 0;

        /** @var DOMElement $tr */
        foreach ($xpath->query('.//tr', $tbody) as $tr) {
            $numero++;

            $celdas = [];
            foreach ($xpath->query('./td', $tr) as $td) {
                $celdas[] = $this->limpiar($td->textContent);
            }

            // Las filas de encabezado de la v1 usan <th>, así que no traen <td>.
            if (count($celdas) === 0) {
                continue;
            }

            if (count($celdas) !== count(self::COLUMNAS)) {
                $avisos[] = "Fila {$numero}: tiene " . count($celdas) . ' celdas en lugar de 8. Se omitió.';
                continue;
            }

            $fila = ['_fila' => $numero];
            foreach (self::COLUMNAS as $i => $clave) {
                $fila[$clave] = $celdas[$i];
            }

            $fila['location_attr'] = $this->limpiar($tr->getAttribute('data-location'));

            // ---- Señales de datos sucios, para el reporte -------------------

            // La v1 filtra por el atributo data-location pero muestra la celda.
            // Cuando no coinciden, el usuario ve una sede y el filtro usa otra.
            if ($fila['location_attr'] !== '' && $fila['location_cell'] !== ''
                && $fila['location_attr'] !== $fila['location_cell']) {
                $fila['_divergencia_sede'] = true;
            }

            if ($fila['email'] === '') {
                $fila['_sin_correo'] = true;
            } elseif (! filter_var($fila['email'], FILTER_VALIDATE_EMAIL)) {
                $fila['_correo_invalido'] = true;
            }

            if ($fila['first_name'] === '' || $fila['last_name'] === '') {
                $fila['_sin_nombre'] = true;
            }

            $filas[] = $fila;
        }

        return ['filas' => $filas, 'avisos' => $avisos];
    }

    /**
     * Normaliza el texto de una celda: quita espacios no separables, colapsa
     * espacios y recorta. No toca acentos ni apóstrofes: O’Connor y Nuño
     * tienen que sobrevivir tal cual.
     */
    private function limpiar(string $texto): string
    {
        $texto = str_replace(["\u{00A0}", "\u{200B}"], ' ', $texto);
        $texto = preg_replace('/\s+/u', ' ', $texto);

        return trim($texto);
    }
}
