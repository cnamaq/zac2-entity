<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity;


interface BuilderInterface
{
    /**
     * @param  string $entity
     * @param  array $arrayData
     * @return EntityAbstract[]
     */
    public function getEntity($entity, array $arrayData);
}