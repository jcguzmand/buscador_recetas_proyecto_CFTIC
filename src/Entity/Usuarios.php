<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UsuariosRepository")
 */
class Usuarios
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
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_alta", type="datetime", nullable=true)
     */
    private $fechaAlta;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recetas", mappedBy="usuario")
     */
    private $recetas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentarios", mappedBy="usuario")
     */
    private $comentarios;

     /**
     * Bidirectional - Many usuarios have Many preguntas favoritas (OWNING SIDE)
     *
     * @ManyToMany(targetEntity="App\Entity\Recetas", inversedBy="usuarioFavoritas")
     * @JoinTable(name="favoritas",
     *      joinColumns={@JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="receta_id", referencedColumnName="id")})
     */
    private $favoritas;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imagen", type="string", length=50, nullable=true)
     */
    private $imagen;

    public function __construct()
    {
        $this->recetas = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->favoritas = new ArrayCollection();
    }

    /**
     * @return Collection|Recetas[]
     */
    public function getRecetas(): Collection
    {
        return $this->recetas;
    }

    /**
     * @return Collection|Comentarios[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    /**
     * @return Collection|Recetas[]
     */
    public function getFavoritas(): Collection
    {
        return $this->favoritas;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(?\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
}
