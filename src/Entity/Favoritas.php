<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favoritas
 *
 * @ORM\Table(name="favoritas", indexes={@ORM\Index(name="fk_favoritas_usuarios_idx", columns={"usuario_id"}), @ORM\Index(name="fk_favoritas_recetas_idx", columns={"receta_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FavoritasRepository")
 */
class Favoritas
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
     * @var \Recetas|null
     *
     * @ORM\ManyToOne(targetEntity="Recetas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receta_id", referencedColumnName="id")
     * })
     */
    private $receta;

    /**
     * @var \Usuarios|null
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceta(): ?Recetas
    {
        return $this->receta;
    }

    public function setReceta(?Recetas $receta): self
    {
        $this->receta = $receta;

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
