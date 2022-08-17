<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recetas;
use App\Entity\Categorias;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
//use Symfony\Component\HttpFoundation\JsonResponse;


class RecetasController extends AbstractController
{
    #[Route('/inicio', name: 'inicio')]
    public function inicio(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator)
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
        dump('$arrayIngredientes en verDetalles', $arrayIngredientes);
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
    public function buscar(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $searchValues = $request->request->get('searchRecetas');
        $searchValuesArray = [];
        $arrayTags = [];
        $recetas = [];

        if (!empty($searchValues)) {
            $searchValuesArray = explode(',', $searchValues);
            //Formar la condición de busqueda para el LIKE
            $conditions = '';
            foreach ($searchValuesArray as $key => $searchValue) {
                if ($key == 0) {
                    $conditions .= 'LIKE :dato' . $key;
                } else {
                    $conditions .= ' AND r.tags LIKE :dato' . $key;
                }
            }

            //Crear la consulta de busqueda
            $query = $em->createQuery("SELECT r FROM App\Entity\Recetas r WHERE r.tags " . $conditions);

            //Configurar parámetros
            foreach ($searchValuesArray as $key => $searchValue) {
                $query->setParameter('dato' . $key, '%' . $searchValues . '%');
            }
            //Ejecutar la consulta
            $recetas = $query->getResult();

            //Configuración del paginador (De momento desactivo el paginador en la busqueda, he creado una vista-element nueva sin paginador recetas_search.html.twig para mostrar el resultado de la busqueda)
            /* $recetas = $paginator->paginate(
            $query,
            // Definir el parámetro de la página recogida por GET
            $request->query->getInt('page', 1),
            // Número de elementos por página
            9
             );  */
            
            if(!empty($recetas)){
                $titulo  = count($recetas) . ' recetas encontradas con esos ingredientes:';
            }else{
                $titulo  = 'Lo sentimos, no hemos encontrado recetas para esos ingredientes. Por favor, vuelva a intentarlo.';
            }
            
            //Crear array con las cadenas de los campos tags
            foreach ($recetas as $receta) {
                $arrayTag = explode(",", $receta->getTags());
                $arrayTags[] = $arrayTag;
            }

        } else {
            $titulo  = 'No ha introducido ningún ingrediente para realizar la busqueda.';
        }
        //Retorno la vista con los datos--------------------------------------------------------------
        return $this->render('elements/recetas_search.html.twig', [
            'recetas'           => $recetas,
            'arrayTags'         => $arrayTags,
            'titulo'            => $titulo,
            'searchValues'      => $searchValues,
            'searchValuesArray' => $searchValuesArray
        ]);
    }

    #[Route('/add_valoracion', name: 'añadirValoracion')]
    public function añadirValoracion(Request $request, EntityManagerInterface $em)
    {
        //Recuperar los datos de la petición AJAX por método post
        $valoracion = $request->request->get('valoracion');
        $idReceta   = $request->request->get('idReceta');

        //Recuperar la receta
        $receta = $em->getRepository(Recetas::class)->find($idReceta);

        //Modificar la valoración de la receta
        $valActual = $receta->getValoracion();
        $valTotal = $valActual + $valoracion;
        $receta->setValoracion($valTotal);

        //Añadir un voto al cómputo de votos de la receta
        $votosActual = $receta->getNumValoraciones();
        $votosTotal = $votosActual + 1;
        $receta->setNumValoraciones($votosTotal);

        //Guardar la receta modificada en la BD
        $em->getRepository(Recetas::class)->add($receta, true);

        //Calcula el promedio de valoracion por voto
        $valMedia = number_format($valTotal / $votosTotal, 1, '.', '');
        
        //Devolver datos en formato json 
        return $this->json([
            'votosTotal'   => $votosTotal,
            'valMedia'      => $valMedia  
        ]);
    }
}
