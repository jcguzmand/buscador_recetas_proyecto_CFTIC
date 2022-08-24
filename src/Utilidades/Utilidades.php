<?php

namespace App\Utilidades;

class Utilidades
{
    const IMG_DEFECTO       = 'img/avatarAnonimo.jpg';

    //Limpia caracteres raros en el nombre de los archivos
    public static function limpiar_archivo($cadena)
    {
        $buscar  = array(' ', '*', '!', '@', '?', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'í', 'ó', 'Ú', 'ñ', 'Ñ', 'Ü', 'ü', '¿', '¡');
        $reemplazar = array('-', '', '', '', '', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'U', 'u', '', '');
        $cadena = str_replace($buscar, $reemplazar, $cadena);
        return $cadena;
    }

    //Redimensionar el tamaño de las imagenes subidas al servidor a un ancho dado en los parámetros y optimizarlas
    public static function optimizar_imagen($imagen, $anchoNuevo)
    {
        //Acceder al archivo de imagen
        $archivo = "img/recetas/" . $imagen;

        //Obtener información del archivo de imagen
        $info = getimagesize($archivo);
        $ancho = $info[0];
        $alto = $info[1];
        $tipo = $info['mime'];

        //Calcular nuevas dimensiones
        $nuevoAncho = $anchoNuevo;
        $factoProp = $anchoNuevo / $ancho;
        $nuevoAlto = $alto * $factoProp;

        //Crear imagen a partir de archivo existente
        $imagen = imagecreatefromjpeg($archivo);

        //Crear el lienzo para la nueva imagen con las nuevas dimensiones
        $lienzo = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

        //Copiar la imagen modificada en el lienzo
        imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

        //Crear el nuevo archivo optimizada a un % dado
        imagejpeg($lienzo, $archivo, 100);
    }
}

