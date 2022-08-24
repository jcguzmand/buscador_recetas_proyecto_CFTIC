<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comentarios;
use App\Entity\Recetas;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;

class ComentariosController extends AbstractController
{
    #[Route('/add_comentario', name: 'añadirComentario')]
    public function añadirComentario(Request $request, EntityManagerInterface $em)
    {
        //Recuperar los datos del formulario a través de AJAX por método post
        $contenido  = $request->request->get('contenido');
        $idReceta   = $request->request->get('idReceta');

        //Recuperar el id del usuario
        //Comprobar si existe la variable de sesión id
        $session = $request->getSession();
        if($existe = $session->has('id')){
            //Recuperamos la variable de sesion con el id del usuario conectado
            $idUsuario = $session->get('id');
        }else{
            //Si el usuario no está registrado será anónimo
            $idUsuario = 4;
        }

        //Recuperar la receta a través de su id
        $receta = $em->getRepository(Recetas::class)->find($idReceta);
        //Recuperar el usuario a través de su id
        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);
        //Obtener la fecha actual
        $date = new \DateTime('now');

        //Crear comentario y rellenar con los datos
        $comentario = new Comentarios;

        $comentario->setContenido($contenido);
        $comentario->setFechaCreacion($date);
        $comentario->setReceta($receta);
        $comentario->setUsuario($usuario);

        //Guardar el comentario en la BD
        $em->getRepository(Comentarios::class)->add($comentario, true);

        //Retorno la vista con los datos--------------------------------------------------------------
        return $this->render('elements/lista_comentarios.html.twig', [
            'receta' => $receta,
            'fecha' => $date
        ]);


    }
}