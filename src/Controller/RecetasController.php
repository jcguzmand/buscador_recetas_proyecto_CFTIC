<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recetas;
use App\Entity\Categorias;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Utilidades\Utilidades;


class RecetasController extends AbstractController
{
    #[Route('/inicio', name: 'inicio')]
    public function inicio(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        dump('$request en inicio', $request);

        //Consulta de recetas por valoración
        $query = $em->createQuery(
            'SELECT r FROM App\Entity\Recetas r 
                ORDER BY r.valoracionMedia DESC'
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
        return $this->render('recetas/inicio.html.twig', [
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
        return $this->render('recetas/inicio.html.twig', [
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
        return $this->render('recetas/inicio.html.twig', [
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
        return $this->render('recetas/detallesReceta.html.twig', [
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
                $query->setParameter('dato' . $key, '%' . $searchValue . '%');
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

        //Calcula la valoración media
        $valMedia = $valTotal / $votosTotal;
        $receta->setvaloracionMedia($valMedia);

        //Guardar la receta modificada en la BD
        $em->getRepository(Recetas::class)->add($receta, true);

        //Formatear la valoración media para mostrar en vista
        $valMedia = number_format($valTotal / $votosTotal, 1, '.', '');
        
        //Devolver datos en formato json 
        return $this->json([
            'votosTotal'   => $votosTotal,
            'valMedia'     => $valMedia  
        ]);
    }

    #[Route('/usuario-recetas', name: 'listarRecetasUsuario')]
    public function listarRecetasUsuario(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        //Obtener el usuario con la sesión activa
        $usuario = $request->getSession()->get('usuario');
        dump('$usuario Usuario con sesión activa en listarRecetasUsuario', $usuario);

        $idUsuario = $usuario->getId();

        //Crear consulta de recetas del usuario
        $query = $em->getRepository(Recetas::class)->findByUsuario($usuario);
        
        //Configuración del paginador
        $recetas = $paginator->paginate(
            $query,
            // Definir el parámetro de la página recogida por GET
            $request->query->getInt('page', 1),
            // Número de elementos por página
            4
        );
        dump('$recetas Recetas del usuario con sesión activa en listarRecetasUsuario', $recetas);

        //Retorno de la vista 
        return $this->render('recetas/listaRecectasUser.html.twig', [
            'recetas' => $recetas
        ]);
    }

    #[Route('/add-receta', name: 'mostrarFormAddReceta')]
    public function mostrarFormAddReceta(Request $request, EntityManagerInterface $em)
    {
        //Creación de la consulta de categorias para cargar en el select del menú
        $categorias = $em->getRepository(Categorias::class)->findAll();
        dump('$categorias', $categorias);

        //Retorno la vista-----------------------------------------------------------------------------
        return $this->render('recetas/addReceta.html.twig', [
            'categorias' => $categorias
        ]);
    }

    #[Route('/execute-add-receta', name: 'ejecutarAddReceta')]
    public function ejecutarAddReceta(Request $request, EntityManagerInterface $em)
    {
        dump('$request, petición en inicio de ejecutarAddReceta', $request);

        //Recuperar datos del formulario
        $nombre         = $request->request->get('nombre');
        $tags           = $request->request->get('tags');
        $ingredientes   = $request->request->get('ingredientes');
        $elaboracion    = $request->request->get('elaboracion');
        $dificultad     = $request->request->get('dificultad');
        $tiempo         = $request->request->get('tiempo');
        $numPersonas    = $request->request->get('numPersonas');
        $nombreCat      = $request->request->get('categoria');

        //Obtener la fecha actual-------------------------------------------------------------------------------------
        $fecha = new \DateTime('now');

        //Obtener el usuario con la sesión activa------------------------------------------------------------------
        $session = $request->getSession();
        $idUsuario = $session->get('id');

        $usuario = $em->getRepository(Usuarios::class)->find($idUsuario);
        dump('$usuario, ejecutarAddReceta', $usuario);

        //Obtener la categoria a partir del nombre----------------------------------------------------------------------------------------
        $categoria = $em->getRepository(Categorias::class)->findOneByNombre($nombreCat);

        //Crear la receta y añadir los datos 
        $receta = new Recetas;
        $receta->setNombre($nombre);
        $receta->setTags($tags);
        $receta->setIngredientes($ingredientes);
        $receta->setElaboracion($elaboracion);
        $receta->setDificultad($dificultad);
        $receta->setTiempo($tiempo);
        $receta->setNumPersonas($numPersonas);
        $receta->setFechaCreacion($fecha);
        $receta->setUsuario($usuario);
        $receta->setCategoria($categoria);
        $receta->setValoracion(0);
        $receta->setNumValoraciones(0);
        $receta->setvaloracionMedia(0);

        //Tratamiento de la imagen para que no de error si no se cambia
        if(!empty($imagen = $_FILES['imagen']['name'])){
            //Recuperar el nombre del archivo de imagen-----------------------------------------------------------------
            $imagen = $_FILES['imagen']['name'];
            //Cambiamos el nombre de la imagen al del nombre de la receta limpiandolo antes de caracteres raros
            $imagen = Utilidades::limpiar_archivo($nombre);
            $imagen = strtolower($imagen) . ".jpg";
            //Copiar el archivo temporal en la ruta del proyecto en el servidor
            copy($_FILES['imagen']['tmp_name'], "img/recetas/" . $imagen);

            //Guardamos la ruta en la receta
            $receta->setImagenUrl($imagen);
        }

        //Guardar la receta en la BD
        $em->getRepository(Recetas::class)->add($receta, true);

        // redirects to the "usuario-recetas" route
        return $this->redirectToRoute('listarRecetasUsuario');
    }

    #[Route('/edit-receta', name: 'mostrarFormEditReceta')]
    public function mostrarFormEditReceta(Request $request, EntityManagerInterface $em)
    {
        //Obtener el id de la receta a editar
        $idReceta = $request->query->get('id');
        //Obtener la receta
        $receta = $em->getRepository(Recetas::class)->find($idReceta);
        dump('$receta', $receta);

        //Recuperar las categorias para cargar en el select del menú
        $categorias = $em->getRepository(Categorias::class)->findAll();
        dump('$categorias', $categorias);

        //Retorno la vista-----------------------------------------------------------------------------
        return $this->render('recetas/editReceta.html.twig', [
            'categorias'    => $categorias,
            'receta'        => $receta
        ]);
    }

    #[Route('/execute-edit-receta', name: 'ejecutarEditReceta')]
    public function ejecutarEditReceta(Request $request, EntityManagerInterface $em)
    {
        dump('$request, petición en inicio de ejecutarAddReceta', $request);

        //Recuperar datos del formulario
        $idReceta       = $request->request->get('idReceta');
        $nombre         = $request->request->get('nombre');
        $tags           = $request->request->get('tags');
        $ingredientes   = $request->request->get('ingredientes');
        $elaboracion    = $request->request->get('elaboracion');
        $dificultad     = $request->request->get('dificultad');
        $tiempo         = $request->request->get('tiempo');
        $numPersonas    = $request->request->get('numPersonas');
        $nombreCat      = $request->request->get('categoria');
   
        //Obtener la categoria a partir del nombre----------------------------------------------------------------------------------------
        $categoria = $em->getRepository(Categorias::class)->findOneByNombre($nombreCat);

        //Crear la receta y añadir los datos 
        $receta = $em->getRepository(Recetas::class)->find($idReceta);
        $receta->setNombre($nombre);
        $receta->setTags($tags);
        $receta->setIngredientes($ingredientes);
        $receta->setElaboracion($elaboracion);
        $receta->setDificultad($dificultad);
        $receta->setTiempo($tiempo);
        $receta->setNumPersonas($numPersonas);
        $receta->setCategoria($categoria);

        //Tratamiento de la imagen para que no de error si no se cambia
        if(!empty($imagen = $_FILES['imagen']['name'])){
            //Recuperar el nombre del archivo de imagen-----------------------------------------------------------------
            $imagen = $_FILES['imagen']['name'];
            //Cambiamos el nombre de la imagen al del nombre de la receta limpiandolo antes de caracteres raros
            $imagen = Utilidades::limpiar_archivo($nombre);
            $imagen = strtolower($imagen) . ".jpg";
            //Copiar el archivo temporal en la ruta del proyecto en el servidor
            copy($_FILES['imagen']['tmp_name'], "img/recetas/" . $imagen);

            //Guardamos la ruta en la receta
            $receta->setImagenUrl($imagen);
        }

        //Guardar la receta en la BD
        $em->getRepository(Recetas::class)->add($receta, true);

        // redirects to the "usuario-recetas" route
        return $this->redirectToRoute('listarRecetasUsuario');
    }

    #[Route('/delete-receta', name: 'borrarReceta')]
    public function borrarReceta(Request $request, EntityManagerInterface $em)
    {   
        //Obtener la id de receta de la petición GET
        $idReceta = $request->request->get('idReceta');
        //Obtener la receta   
        $receta = $em->getRepository(Recetas::class)->find($idReceta);
        //Borrar la receta
        $em->getRepository(Recetas::class)->remove($receta, true);

        //Devolver datos en formato json 
        return $this->json([
            'mensaje'   => 'La receta se ha borrado correctamente'
        ]);
    }
}
