<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipo
 *
 * @ORM\Table(name="tipo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoRepository")
 */
class Tipo
{
     //Relaciones
    
    //Relacion con la tabla Producto
    
    /**
    * @ORM\OneToMany(targetEntity="Producto", mappedBy="tipo")
    */
    
    private $producto;

    public function __construct() {
        $this->producto = new ArrayCollection();
    }
    
     // Relacion con la tabla categoria
    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="tipo")
     * @ORM\JoinColumn(name="categoriaId", referencedColumnName="id")
     */
     private $categoria; 
     
    
     
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="categoriaId", type="integer")
     */
    private $categoriaId;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Tipo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set categoriaId
     *
     * @param integer $categoriaId
     *
     * @return Tipo
     */
    public function setCategoriaId($categoriaId)
    {
        $this->categoriaId = $categoriaId;

        return $this;
    }

    /**
     * Get categoriaId
     *
     * @return int
     */
    public function getCategoriaId()
    {
        return $this->categoriaId;
    }
    
    /**
     * Set categoria
     *
     * @param integer $categoriaId
     *
     * @return Tipo
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return int
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}

