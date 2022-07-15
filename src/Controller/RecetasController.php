<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recetas;
use Doctrine\ORM\EntityManagerInterface;

class RecetasController extends AbstractController
{
    #[Route('/detalles', name: 'verDetalles')]
    public function verDetalles(Request $request, EntityManagerInterface $em)
    {
        dump('$request->query en verDetalles', $request->query);
        //Obtener el id de la receta envíada a través del enlace por el método GET
        $id = $request->query->get('id');
        //Obtener la entidad receta
        $receta = $em->getRepository(Recetas::class)->find($id);
        dump('$receta en verDetalles', $receta);

        //Retorno la vista con la receta
        return $this->render('detallesReceta.html.twig', [
            'receta' => $receta
        ]);
    }
}