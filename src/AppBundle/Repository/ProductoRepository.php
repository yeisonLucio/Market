<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Producto;


/**
 * ProductoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductoRepository extends \Doctrine\ORM\EntityRepository
{
    public function listarProductos()
    {
        $consulta = $this->getEntityManager()->createQuery(
            'SELECT p.nombre,p.codigo,p.marca,p.descripcion,p.precio,p.color,p.estado,p.calificacion,i.ruta
            FROM AppBundle\Entity\Producto p
            LEFT JOIN AppBundle\Entity\Imagen i
            WHERE p.id=i.productoId
            AND i.portada = 1'
        );
            $valor = $consulta->getResult();
        return $valor;

    }

   
}
