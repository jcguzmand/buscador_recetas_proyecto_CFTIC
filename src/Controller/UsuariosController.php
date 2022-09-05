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

    #[Route('/execute-register', name: 'registrarUsuario')]
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

        //Iniciar sesión
        $session = $request->getSession();
        //Crear variables de sesión
        $session->set('usuario', $usuario);
        $session->set('id', $usuario->getId());

        //Devolver la vista de bienvenida
        return $this->render('usuarios/confirmRegistroUsuario.html.twig');
    }

    #[Route('/comprobar_nombre_user', name: 'comprobarNombre')]
    public function comprobarNombre(Request $request, EntityManagerInterface $em)
    {
        //Obtener nombre del formulario
        $nombre = $request->request->get('nombre');

        $usuarios = $em->getRepository(Usuarios::class)->findAll();

        //Comprobar que el nombre de usuario introducido no existe en la BD
        foreach ($usuarios as $usuario) {
            if (strtolower($usuario->getNombre()) == strtolower($nombre)) {
                //Si existe un nombre igual devolvemos salimos del método y devolvemos false 
                return $this->json([
                    'resultado' => false
                ]);
            }
        }

        //Si el nombre no existe devolvemos true
        return $this->json([
            'resultado' => true
        ]);
    }

    #[Route('/comprobar_password_user', name: 'comprobarPassword')]
    public function comprobarPassword(Request $request, EntityManagerInterface $em)
    {
        //Obtener el usuario con la sesión activa------------------------------------------------------------------
        $session = $request->getSession();
        $idUsuario = $session->get('id');

        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

        //Obtener la contraseña del fomulario a través de ajax
        $password_Form = $request->request->get('password');

        //Recuperar la contraseña de la BD
        $password_hash_BD = $usuario->getPassword();

        //Comprobar que pass del formulario y de la BD son iguales
        if (password_verify($password_Form, $password_hash_BD)) {

            //Devolver resultado OK al método Ajax
            return $this->json([
                'resultado' => true
            ]);
        } else {
            //Las contraseñas no son iguales
            return $this->json([
                'resultado' => false
            ]);
        }
    }

    #[Route('/conectar', name: 'mostrarFormularioLogin')]
    public function mostrarFormularioLogin()
    {
        return $this->render('usuarios/loginUsuario.html.twig');
    }

    #[Route('/comprobar_credenciales', name: 'conectarUsuario')]
    public function conectarUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener credenciales de usuario del formulario
        $email          = $request->request->get('email');
        $password_Form  = $request->request->get('password');

        //Recuperar el usuario de la BD según el email introducido
        $usuario = $em->getRepository(Usuarios::class)->findOneByEmail($email);

        //Comprobar en primer lugar que el usuario existe
        if ($usuario) {
            //Recuperar la contraseña de la BD
            $password_hash = $usuario->getPassword();

            //Comprobar que pass del formulario y de la BD son iguales
            if (password_verify($password_Form, $password_hash)) {

                //Iniciar sesión
                $session = $request->getSession();
                //Crear variables de sesión
                $session->set('usuario', $usuario);
                $session->set('id', $usuario->getId());

                //Devolver resultado OK de login al método Ajax
                return $this->json([
                    'resultado' => true
                ]);
            } else {
                //Error de contraseña
                return $this->json([
                    'resultado' => false
                ]);
            }
        } else {
            //Error de usuario
            return $this->json([
                'resultado' => false
            ]);
        }
    }

    #[Route('/desconectar', name: 'desconectarUsuario')]
    public function desconectarUsuario(Request $request)
    {
        $session = $request->getSession();
        $session->invalidate();

        // redirects to the "inicio" route
        return $this->redirectToRoute('inicio');
    }

    #[Route('/perfil-user', name: 'verPerfilUsuario')]
    public function verPerfilUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener el usuario con la sesión activa------------------------------------------------------------------
        $session = $request->getSession();
        $idUsuario = $session->get('id');

        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

        //Retorno la vista-----------------------------------------------------------------------------
        return $this->render('usuarios/perfilUsuario.html.twig', [
            'usuario' => $usuario
        ]);
    }

    #[Route('/edit-image-user', name: 'editarImagenUsuario')]
    public function editarImagenUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener el usuario con la sesión activa------------------------------------------------------------------
        $session = $request->getSession();
        $idUsuario = $session->get('id');

        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);
        $nombre = $usuario->getNombre();

        //Recuperar el archivo con la imagen de la petición de Ajax
        $file = $request->files->get('file');

        //Cambiamos el nombre de la imagen al del nombre de la receta limpiandolo antes de caracteres raros
        $imagen = Utilidades::limpiar_archivo_sin_random($nombre);
        $imagen = strtolower($imagen) . ".jpg";
        //Copiar el archivo de la imagen en la ruta del proyecto en el servidor
        copy($file, "img/usuarios/" . $imagen);

        //Guardamos la ruta en la receta
        $usuario->setImagen($imagen);

        //Guardar el usuario modificado en la BD
        $em->getRepository(Usuarios::class)->add($usuario, true);

        //Devolver datos en formato json 
        return $this->json([
            'resultado'  => true
        ]);
    }

    #[Route('/edit-name-user', name: 'editarNombreUsuario')]
    public function editarNombreUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener el nombre del formulario de la petición ajax
        $nombre  = $request->request->get('nombre');

        //Obtener el usuario con la sesión activa------------------------------------------------------------------
        $session = $request->getSession();
        $idUsuario = $session->get('id');
        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

        //Modificar el campo nombre del usuario
        $usuario->setNombre($nombre);

        //Guardar el usuario modificado en la BD
        $em->getRepository(Usuarios::class)->add($usuario, true);

        //Devolver datos en formato json 
        return $this->json([
            'resultado'  => true
        ]);
    }

    #[Route('/edit-email-user', name: 'editarEmailUsuario')]
    public function editarEmailUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener el nombre del formulario de la petición ajax
        $email = $request->request->get('email');

        //Obtener el usuario con la sesión activa------------------------------------------------------------------
        $session = $request->getSession();
        $idUsuario = $session->get('id');
        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

        //Modificar el campo nombre del usuario
        $usuario->setEmail($email);

        //Guardar el usuario modificado en la BD
        $em->getRepository(Usuarios::class)->add($usuario, true);

        //Devolver datos en formato json 
        return $this->json([
            'resultado'  => true
        ]);
    }

    #[Route('/edit-password-user', name: 'editarPasswordUsuario')]
    public function editarPasswordUsuario(Request $request, EntityManagerInterface $em)
    {
        //Obtener el password del formulario de la petición ajax
        $passwordNuevo = $request->request->get('passwordNuevo');

        //Hashear el password usando una constante como semilla
        $passwordNuevo_Hash = password_hash($passwordNuevo, PASSWORD_DEFAULT);

        //Obtener el usuario con la sesión activa------------------------------------------------------------------
        $session = $request->getSession();
        $idUsuario = $session->get('id');
        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

        //Modificar el campo nombre del usuario
        $usuario->setPassword($passwordNuevo_Hash);

        //Guardar el usuario modificado en la BD
        $em->getRepository(Usuarios::class)->add($usuario, true);

        //Devolver datos en formato json 
        return $this->json([
            'resultado'  => true
        ]);
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
