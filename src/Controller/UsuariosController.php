<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;

class UsuariosController extends AbstractController
{
    #[Route('/detallesUsuario', name: 'detallesUsuario')]
    public function detallesUsuario(Request $request, EntityManagerInterface $em)
    {
        //dump('$request->query en verDetalles', $request->query);
        //Obtener el id de la receta envíada a través del enlace por el método GET
        //$id = $request->query->get('id');
        //Obtener la entidad receta-----------------------------------------------------------------
        $usuario = $em->getRepository(Usuarios::class)->find(1);
        dump('$usuario en inicio', $usuario);
        
        //Retorno la vista con los datos--------------------------------------------------------------
        return $this->render('detallesUsuario.html.twig', [
            'usuario' => $usuario,
        ]);
    }
}