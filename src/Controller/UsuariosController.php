<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;
use App\Utilidades\Utilidades;

class UsuariosController extends AbstractController
{
    #[Route('/register', name: 'mostrarFormularioRegistro')]
    public function mostrarFormularioRegistro(Request $request, EntityManagerInterface $em)
    {
        return $this->render('usuarios/registroUsuario.html.twig');
    }

    #[Route('/welcome', name: 'registrarUsuario')]
    public function registrarUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener datos de usuario del formulario
        $nombre     = $request->request->get('nombre');
        $email      = $request->request->get('email');
        $password   = $request->request->get('password');

        //Hashear el password usando una constante como semilla
        $password   = password_hash($password, PASSWORD_DEFAULT);
        //Obteber la url de la imagen por defecto
        $imagen     = Utilidades::IMG_DEFECTO;
        //Obtener la fecha actual
        $fecha = new \DateTime('now');

        //Instanciar la clase Usuario y setear con los datos recuperados
        $usuario = new Usuarios;
        $usuario->setNombre($nombre);
        $usuario->setEmail($email);
        $usuario->setPassword($password);
        $usuario->setFechaAlta($fecha);
        $usuario->setImagen($imagen);

        //Guardar el usuario en la BD
        $em->getRepository(Usuarios::class)->add($usuario, true);

        //Retorno la vista con los datos--------------------------------------------------------------
        return $this->render('usuarios/confirmRegistroUsuario.html.twig');
    }

    #[Route('/conectar', name: 'mostrarFormularioLogin')]
    public function mostrarFormularioLogin(Request $request, EntityManagerInterface $em)
    {
        return $this->render('usuarios/loginUsuario.html.twig');
    }

    #[Route('/comprobar_credenciales', name: 'conectarUsuario')]
    public function conectarUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener credenciales de usuario del formulario
        $email      = $request->request->get('email');
        $password   = $request->request->get('password');

        if(1 == 1){
            //Devolver datos en formato json 
            return $this->json([
                'resultado'   => false  
        ]);

        }else{
            return $this->render('usuarios/loginUsuario.html.twig');
        }
        
    }













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
        return $this->render('usuarios/detallesUsuario.html.twig', [
            'usuario'       => $usuario
        ]);
    }
}