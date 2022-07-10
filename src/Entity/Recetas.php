<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Recetas
 *
 * @ORM\Table(name="recetas", indexes={@ORM\Index(name="fk_recetas_usuarios_idx", columns={"usuario_id"}), @ORM\Index(name="fk_recetas_categorias_idx", columns={"categoria_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RecetasRepository")
 */
class Recetas
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tags", type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ingredientes", type="text", length=65535, nullable=true)
     */
    private $ingredientes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="elaboracion", type="text", length=65535, nullable=true)
     */
    private $elaboracion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imagen_url", type="string", length=255, nullable=true)
     */
    private $imagenUrl;

    /**
     * @var array|null
     *
     * @ORM\Column(name="dificultad", type="simple_array", length=0, nullable=true)
     */
    private $dificultad;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tiempo", type="integer", nullable=true)
     */
    private $tiempo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="num_personas", type="integer", nullable=true)
     */
    private $numPersonas;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var float|null
     *
     * @ORM\Column(name="valoracion", type="float", precision=2, scale=1, nullable=true)
     */
    private $valoracion;

    /**
     * @var int|null
     *
     * @ORM\Column(name="num_valoraciones", type="integer", nullable=true)
     */
    private $numValoraciones;

    /**
     * @var \Categorias|null
     *
     * @ORM\ManyToOne(targetEntity="Categorias", inversedBy="recetas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     * })
     */
    private $categoria;

    /**
     * @var \Usuarios|null
     *
     * @ORM\ManyToOne(targetEntity="Usuarios", inversedBy="recetas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * Bidirectional - Many recetas are favorited by many usuarios (INVERSE SIDE)
     *
     * @ManyToMany(targetEntity="Usuarios", mappedBy="favoritas")
     */
    private $usuarioFavoritas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentarios", mappedBy="usuario")
     */
    private $comentarios;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
    }

    /**
     * @return Collection|Comentarios[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getIngredientes(): ?string
    {
        return $this->ingredientes;
    }

    public function setIngredientes(?string $ingredientes): self
    {
        $this->ingredientes = $ingredientes;

        return $this;
    }

    public function getElaboracion(): ?string
    {
        return $this->elaboracion;
    }

    public function setElaboracion(?string $elaboracion): self
    {
        $this->elaboracion = $elaboracion;

        return $this;
    }

    public function getImagenUrl(): ?string
    {
        return $this->imagenUrl;
    }

    public function setImagenUrl(?string $imagenUrl): self
    {
        $this->imagenUrl = $imagenUrl;

        return $this;
    }

    public function getDificultad(): ?array
    {
        return $this->dificultad;
    }

    public function setDificultad(?array $dificultad): self
    {
        $this->dificultad = $dificultad;

        return $this;
    }

    public function getTiempo(): ?int
    {
        return $this->tiempo;
    }

    public function setTiempo(?int $tiempo): self
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    public function getNumPersonas(): ?int
    {
        return $this->numPersonas;
    }

    public function setNumPersonas(?int $numPersonas): self
    {
        $this->numPersonas = $numPersonas;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(?\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getValoracion(): ?float
    {
        return $this->valoracion;
    }

    public function setValoracion(?float $valoracion): self
    {
        $this->valoracion = $valoracion;

        return $this;
    }

    public function getNumValoraciones(): ?int
    {
        return $this->numValoraciones;
    }

    public function setNumValoraciones(?int $numValoraciones): self
    {
        $this->numValoraciones = $numValoraciones;

        return $this;
    }

    public function getCategoria(): ?Categorias
    {
        return $this->categoria;
    }

    public function setCategoria(?Categorias $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getUsuario(): ?Usuarios
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuarios $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }


}
