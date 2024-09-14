<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;

class VideoController extends Controller
{
    private $s3;
    private $cloudFrontBaseUrl = 'https://d11dzzmlbj38ir.cloudfront.net/'; // Base de la URL para CloudFront

    public function __construct()
    {
        // Configuración de S3
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Mostrar la página de subida de archivo.
     */
    public function showUploadForm()
    {
        return view('videoform');
    }

    /**
     * Función privada para manejar la subida de archivos a S3.
     */
    private function uploadToS3(Request $request, $folderPath, $fileInputName, $formType)
    {
        // Validación del archivo
        $request->validate([
            $fileInputName => 'required|mimes:mp4,mov,ogg,qt,jpg,jpeg,png,pdf|max:800000',
        ]);

        // Obtener el archivo del request
        $file = $request->file($fileInputName);
        $filePath = $folderPath . $file->getClientOriginalName();
        $mimeType = $file->getMimeType(); // Obtener el tipo MIME

        try {
            // Subir el archivo a S3
            $result = $this->s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $filePath,
                'SourceFile' => $file->getPathname(),
                'ContentType' => $mimeType,
            ]);

            // Notificación de éxito específica por formulario
            return redirect()->back()->with($formType . '_success', 'Archivo subido exitosamente!');
        } catch (\Exception $e) {
            // Notificación de error específica por formulario
            return redirect()->back()->with($formType . '_error', 'Error al subir el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Subida para Galerías
     */
    public function uploadGaleria(Request $request)
    {
        return $this->uploadToS3($request, 'uploads/galerias/', 'file_galeria', 'galeria');
    }

    /**
     * Subida para Boletines
     */
    public function uploadBoletines(Request $request)
    {
        return $this->uploadToS3($request, 'uploads/boletines/', 'file_boletines', 'boletines');
    }

    /**
     * Subida para Documentos
     */
    public function uploadDocumentos(Request $request)
    {
        return $this->uploadToS3($request, 'uploads/documentos/', 'file_documentos', 'documentos');
    }

    /**
     * Mostrar la galería con los archivos de S3.
     */
    public function showGallery()
    {
        return $this->listFilesFromS3('uploads/galerias/', 'gallery');
    }

    /**
     * Mostrar la lista de boletines con los archivos de S3.
     */
    public function showBoletines()
    {
        return $this->listFilesFromS3('uploads/boletines/', 'boletines');
    }

    /**
     * Mostrar la lista de documentos con los archivos de S3.
     */
    public function showDocumentos()
    {
        return $this->listFilesFromS3('uploads/documentos/', 'document');
    }

    /**
     * Función privada para obtener archivos desde S3 y mostrar la vista.
     */
    private function listFilesFromS3($folderPath, $viewName)
    {
        try {
            // Obtener lista de objetos en el bucket desde el prefijo específico
            $results = $this->s3->listObjectsV2([
                'Bucket' => env('AWS_BUCKET'),
                'Prefix' => $folderPath
            ]);

            $files = [];
            foreach ($results['Contents'] as $object) {
                $fileName = pathinfo($object['Key'], PATHINFO_BASENAME);
                $fileExtension = pathinfo($object['Key'], PATHINFO_EXTENSION);

                // Construir la URL con la base de CloudFront incluyendo el subdirectorio adecuado
                $cloudFrontUrl = $this->cloudFrontBaseUrl . $folderPath . urlencode($fileName);

                // Categorizar archivo según su tipo
                $files[] = [
                    'url' => $cloudFrontUrl,
                    'extension' => strtolower($fileExtension),
                    'name' => $fileName
                ];
            }

            return view($viewName, ['files' => $files]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al obtener los archivos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar la página de reproducción de video.
     */
    public function showVideo(Request $request)
    {
        $videoTitle = $request->input('title');
        $videoPath = $request->input('path');
        return view('video', ['videoTitle' => $videoTitle, 'videoPath' => $videoPath]);
    }
}
