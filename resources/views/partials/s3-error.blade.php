{{--
    Aviso que se muestra cuando no se pudo leer el bucket de S3.

    Antes, si S3 fallaba, el controlador hacia redirect()->back() y la seccion
    simplemente aparecia vacia o rebotaba al inicio, sin ninguna pista de que
    habia pasado. Este bloque hace visible la causa real (credenciales, red,
    certificado SSL faltante, permisos del bucket) en vez de dejarla oculta.
    El detalle tecnico completo tambien queda en storage/logs/laravel.log.
--}}
<div style="margin: 0 0 1.5rem; padding: 1rem 1.25rem; border: 1px solid #e0b4b4;
            border-left: 4px solid #c0392b; border-radius: 6px; background: #fdf3f2;
            color: #7d2b1c; font-size: 0.95rem; line-height: 1.5;">
    <strong style="display: block; margin-bottom: 0.35rem; color: #c0392b;">
        No se pudieron cargar los archivos
    </strong>
    <span>{{ $error }}</span>
</div>
