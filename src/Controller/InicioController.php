<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recetas;
use Doctrine\ORM\EntityManagerInterface;

class InicioController extends AbstractController
{
    #[Route('/inicio', name: 'inicio')]
    public function inicio(Request $request, EntityManagerInterface $em)
    {
        //Creacion de la consulta de recetas por valoración-----------------------------------------------------
        $queryByVal = $em->createQuery('SELECT r FROM App\Entity\Recetas r ORDER BY r.valoracion DESC');
        //Establecer el limit de la consulta, max 6 registros
        $queryByVal->setFirstResult(0);
        $queryByVal->setMaxResults(6);
        //Ejecución de la consulta
        $recetasByVal = $queryByVal->getResult();
        dump('$recetasByVal en inicio', $recetasByVal);

        //Creacion de la consulta de recetas por fecha----------------------------------------------------------
        $queryByFecha = $em->createQuery('SELECT r FROM App\Entity\Recetas r ORDER BY r.fechaCreacion DESC');
        //Establecer el limit de la consulta, max 6 registros
        $queryByFecha->setFirstResult(0);
        $queryByFecha->setMaxResults(6);
        //Ejecución de la consulta
        $recetasByFecha = $queryByFecha->getResult();

        //Retorno de la vista con las recetas
        return $this->render('inicio.html.twig', [
           'recetasByVal'   => $recetasByVal,
           'recetasByFecha' => $recetasByFecha
        ]);
    }
}