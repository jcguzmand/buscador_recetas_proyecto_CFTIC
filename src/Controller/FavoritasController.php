<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Favoritas;
use App\Entity\Recetas;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;

class FavoritasController extends AbstractController
{
    #[Route('/add-favorita', name: 'añadirFavorita')]
    public function añadirFavorita(Request $request, EntityManagerInterface $em)
    {
        //Obtener la receta de la petición post de ajax
        $idReceta = $request->request->get('idReceta'); //$idReceta = 1;
        $receta = $em->getRepository(Recetas::class)->find($idReceta);

        //Obtener el usuario con la sesión activa
        $session = $request->getSession();
        $idUsuario = $session->get('id');//$idUsuario = 2;
        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

        //Crear favorita y rellenar con los datos
        $favorita = new Favoritas;
        $favorita->setReceta($receta);
        $favorita->setUsuario($usuario);

        //Guardar la favorita en la BD
        $em->getRepository(Favoritas::class)->add($favorita, true);

        //Devolver datos en formato json 
        return $this->json([
            'resultado'  => true
        ]);
    }

    #[Route('/delete-favorita', name: 'eliminarFavorita')]
    public function eliminarFavorita(Request $request, EntityManagerInterface $em)
    {
        //Obtener la receta de la petición post de ajax
        $idReceta = $request->request->get('idReceta');
        //$idReceta = 3; 
        $receta = $em->getRepository(Recetas::class)->find($idReceta);

        //Iniciar sesión
        $session = $request->getSession();

        //Comprobar que la sesión existe
        if($session->has('id')){
            //Recuperamos el usuario de la sesión
            $idUsuario = $session->get('id');
            $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

            //Recuperamos la favorita segun la receta y el usuario con sesion activa
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Favoritas f
                    WHERE f.receta = :dato1
                    AND f.usuario = :dato2'
            );

            $idUsuario = $session->get('id');
            
            $query->setParameter('dato1', $receta);
            $query->setParameter('dato2', $usuario);

            $favoritaArray = $query->getResult();
            $favorita = $favoritaArray[0];
        }

        dump('$favorita', $favorita);
        
        //Elmininar la favorita de la BD
        $em->getRepository(Favoritas::class)->remove($favorita, true);

        //Devolver datos en formato json 
        return $this->json([
            'resultado'  => true
        ]);
    }

    #[Route('/delete-favorita-list', name: 'eliminarFavoritaListado')]
    public function eliminarFavoritaListado(Request $request, EntityManagerInterface $em)
    {
        //Obtener la receta de la petición post de ajax
        $idReceta = $request->request->get('idReceta');
        $receta = $em->getRepository(Recetas::class)->find($idReceta);

        //Iniciar sesión
        $session = $request->getSession();

        //Comprobar que la sesión existe
        if($session->has('id')){
            //Recuperamos el usuario de la sesión
            $idUsuario = $session->get('id');
            $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

            //Recuperamos la favorita segun la receta y el usuario con sesion activa
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Favoritas f
                    WHERE f.receta = :dato1
                    AND f.usuario = :dato2'
            );

            $idUsuario = $session->get('id');
            
            $query->setParameter('dato1', $receta);
            $query->setParameter('dato2', $usuario);

            $favoritaArray = $query->getResult();
            $favorita = $favoritaArray[0];
        }
        dump('$favorita', $favorita);
        
        //Elmininar la favorita de la BD
        $em->getRepository(Favoritas::class)->remove($favorita, true);

        //Recuperar el listado de favoritas actualizado
        $favoritas = $em->getRepository(Favoritas::class)->findByUsuario($usuario);

        //Retorno de la vista con peticiones GET
        return $this->render('elements/lista_favoritas_table.html.twig', [
            'favoritas' => $favoritas
        ]);
    }

    #[Route('/list-favoritas', name: 'listarFavoritas')]
    public function listarFavoritas(Request $request, EntityManagerInterface $em)
    {
        $favoritas = [];

        //Iniciar sesión
        $session = $request->getSession();
        //Recuperamos el usuario de la sesión
        $idUsuario = $session->get('id');
        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);

        $favoritas = $em->getRepository(Favoritas::class)->findByUsuario($usuario);

        dump($favoritas);
        
        //Retorno de la vista con peticiones GET
        return $this->render('favoritas/lista_favoritas.html.twig', [
            'favoritas' => $favoritas
        ]);
    }
}
