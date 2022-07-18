<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorias;
use App\Entity\Recetas;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class InicioController extends AbstractController
{
    #[Route('/inicio', name: 'inicio')]
    public function inicio(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator,)
    {
        dump('$request en inicio', $request);

        if ($request->server->get('REQUEST_METHOD') == 'POST') {
            dump('Entramos en POST------------------------------------------------------------------------------------');
            //Busqueda con AJAX
            $titulo  = 'No se han encontrado coincidencias. Vuelva a intentarlo cambiando los ingredientes.';
            $titulo  = 'Coincidencias encontradas: ';


        } else {
            dump('Entramos en GET------------------------------------------------------------------------------------');
            if (($request->query->get('idCategoria')) !== null) {
                //Consulta de recetas por categoria---------------------------------------------------------------------
                $idCategoria = $request->query->get('idCategoria');
                $query = $em->getRepository(Recetas::class)->findBy(
                    ['categoria' => $idCategoria],
                    ['valoracion' => 'DESC']
                );

                //Colocar aqui un bloque try cath para controlar si no hay recetas en la categori#######################
                $recetas = $paginator->paginate(
                    $query,
                    // Definir el parámetro de la página recogida por GET
                    $request->query->getInt('page', 1),
                    // Número de elementos por página
                    1
                );
                //######################################################################################################

                //Consulta para obtener el nombre de la categoria y generar el título
                if (!empty($recetas)) {
                    $nombreCat = $recetas[0]->getCategoria()->getNombre();
                    $titulo  = 'Recetas de ' . strtolower($nombreCat);
                } else {
                    $titulo  = 'Todavía no existen recetas en esta categoría';
                }
            } else {
                //Consulta de recetas por valoración----------------------------------------------------------------------
                $query = $em->createQuery(
                    'SELECT r FROM App\Entity\Recetas r 
                        ORDER BY r.valoracion DESC'
                );
                //Establecer el limit de la consulta, max 9 registros
                $query->setFirstResult(0);
                $query->setMaxResults(12);
                //Configuración del paginador
                $recetas = $paginator->paginate(
                    $query,
                    // Definir el parámetro de la página recogida por GET
                    $request->query->getInt('page', 1),
                    // Número de elementos por página
                    6
                );
                $titulo  = 'Selección de recetas más valoradas';
            }
            //Creación de la consulta de categorias para cargar en el select del menú
            $categorias = $em->getRepository(Categorias::class)->findAll();
        }

        //Crear array con las cadenas de los campos tags-------------------------------------------------------------------
        $arrayTags = [];
        foreach ($recetas as $receta) {
            $arrayTag = explode(",", $receta->getTags());
            $arrayTags[] = $arrayTag;
        }

        dump('$arrayTags en inicio', $arrayTags);
        dump('$recetas en inicio', $recetas);

        //Generación de las vistas según el tipo de petición----------------------------------------------------------------
        if ($request->server->get('REQUEST_METHOD') == 'POST') {
            //Retorno de la vista con peticiones POST con AJAX
            return $this->render('elements/recetas_inicio.html.twig', [
                'recetas'    => $recetas,
                'arrayTags'  => $arrayTags,
                'categorias' => $categorias,
                'titulo'     => $titulo
            ]);
        } else {
            //Retorno de la vista con peticiones GET
            return $this->render('inicio.html.twig', [
                'recetas'    => $recetas,
                'arrayTags'  => $arrayTags,
                'categorias' => $categorias,
                'titulo'     => $titulo
            ]);
        }
    }
}
