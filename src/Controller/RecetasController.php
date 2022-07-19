<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recetas;
use App\Entity\Categorias;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


class RecetasController extends AbstractController
{
    #[Route('/inicio', name: 'inicio')]
    public function inicio(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator,)
    {
        dump('$request en inicio', $request);

        //Consulta de recetas por valoración
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

        //Crear array con las cadenas de los campos tags
        $arrayTags = [];
        foreach ($recetas as $receta) {
            $arrayTag = explode(",", $receta->getTags());
            $arrayTags[] = $arrayTag;
        }

        dump('$arrayTags en inicio', $arrayTags);
        dump('$recetas en inicio', $recetas);

        //Creación de la consulta de categorias para cargar en el select del menú
        $categorias = $em->getRepository(Categorias::class)->findAll();

        //Retorno de la vista con peticiones GET
        return $this->render('inicio.html.twig', [
            'recetas'    => $recetas,
            'arrayTags'  => $arrayTags,
            'categorias' => $categorias,
            'titulo'     => $titulo
        ]);
    }

    #[Route('/filtro_categoria', name: 'filtroCategoria')]
    public function filtroCategoria(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator,)
    {
        dump('$request en filtroCategoria', $request);

        //Consulta de recetas por categoria
        $idCategoria = $request->query->get('id');
        $query = $em->getRepository(Recetas::class)->findBy(
            ['categoria' => $idCategoria],
            ['valoracion' => 'DESC']
        );

        $recetas = $paginator->paginate(
            $query,
            // Definir el parámetro de la página recogida por GET
            $request->query->getInt('page', 1),
            // Número de elementos por página
            1
        );

        //Consulta para obtener el nombre de la categoria y generar el título
        if (isset($recetas[0])) {
            $nombreCat = $recetas[0]->getCategoria()->getNombre();
            $titulo  = 'Recetas de ' . strtolower($nombreCat);
        } else {
            $titulo  = 'Lo sentimos, todavía no existen recetas en esta categoría';
        }

        //Crear array con las cadenas de los campos tags
        $arrayTags = [];
        foreach ($recetas as $receta) {
            $arrayTag = explode(",", $receta->getTags());
            $arrayTags[] = $arrayTag;
        }

        dump('$arrayTags en inicio', $arrayTags);
        dump('$recetas en inicio', $recetas);

        //Creación de la consulta de categorias para cargar en el select del menú
        $categorias = $em->getRepository(Categorias::class)->findAll();

        //Retorno de la vista con peticiones GET
        return $this->render('inicio.html.twig', [
            'recetas'    => $recetas,
            'arrayTags'  => $arrayTags,
            'categorias' => $categorias,
            'titulo'     => $titulo
        ]);
    }

    #[Route('/filtro_ingred', name: 'filtroIngrediente')]
    public function filtroIngrediente(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator,)
    {
        dump('$request en filtroIngrediente', $request);

        //Consulta de recetas por categoria
        $nombreIngred = $request->query->get('nombre');
        //Consulta de recetas por valoración
        $query = $em->createQuery(
            'SELECT r FROM App\Entity\Recetas r
                WHERE r.tags LIKE :dato
                ORDER BY r.valoracion DESC'
        );
        $query->setParameter('dato', '%' . $nombreIngred . '%');

        $recetas = $paginator->paginate(
            $query,
            // Definir el parámetro de la página recogida por GET
            $request->query->getInt('page', 1),
            // Número de elementos por página
            1
        );

        //Consulta para obtener el nombre de la categoria y generar el título
        if (isset($recetas[0])) {
            $titulo  = 'Recetas con ' . strtolower($nombreIngred);
        } else {
            $titulo  = 'Lo sentimos, no existen recetas con ese ingrediente';
        }

        //Crear array con las cadenas de los campos tags
        $arrayTags = [];
        foreach ($recetas as $receta) {
            $arrayTag = explode(",", $receta->getTags());
            $arrayTags[] = $arrayTag;
        }

        dump('$arrayTags en filtro_ingred', $arrayTags);
        dump('$recetas en filtro_ingred', $recetas);

        //Creación de la consulta de categorias para cargar en el select del menú
        $categorias = $em->getRepository(Categorias::class)->findAll();

        //Retorno de la vista con peticiones GET
        return $this->render('inicio.html.twig', [
            'recetas'    => $recetas,
            'arrayTags'  => $arrayTags,
            'categorias' => $categorias,
            'titulo'     => $titulo
        ]);
    }

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

        //Creación de la consulta de categorias para cargar en el select del menú
        $categorias = $em->getRepository(Categorias::class)->findAll();
        
        //Retorno la vista con los datos--------------------------------------------------------------
        return $this->render('detallesReceta.html.twig', [
            'receta'            => $receta,
            'arrayIngredientes' => $arrayIngredientes,
            'categorias'        => $categorias
        ]);
    }

    #[Route('/buscar', name: 'buscar')]
    public function buscar(Request $request, EntityManagerInterface $em)
    {



        
        //Retorno la vista con los datos--------------------------------------------------------------
        return $this->render('elements/recetas_inicio.html.twig', [
            'recetas'    => $recetas,
            'arrayTags'  => $arrayTags,
            'titulo'     => $titulo
        ]);

    }
}