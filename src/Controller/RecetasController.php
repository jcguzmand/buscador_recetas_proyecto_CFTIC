<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recetas;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Intl\Languages;

class RecetasController extends AbstractController
{
    #[Route('/detalles', name: 'verDetalles')]
    public function verDetalles(Request $request, EntityManagerInterface $em)
    {
        dump('$request->query en verDetalles', $request->query);
        //Obtener el id de la receta envíada a través del enlace por el método GET
        $id = $request->query->get('id');
        //Obtener la entidad receta-----------------------------------------------------------------
        $receta = $em->getRepository(Recetas::class)->find($id);
        //Crear array con la cadena del campo ingredientes------------------------------------------
        $arrayIngredientes = explode(",", $receta->getIngredientes());
        dump('$arrayIngredientes en inicio', $arrayIngredientes);
        dump('$receta en verDetalles', $receta);

        //Obtener el usuario-------------------------------------------------------------------------
        $usuario = $receta->getUsuario();
        dump('$usuario en verDetalles', $usuario);
        
        //Retorno la vista con los datos--------------------------------------------------------------
        return $this->render('detallesReceta.html.twig', [
            'receta'            => $receta,
            'arrayIngredientes' => $arrayIngredientes,
        ]);
    }
}