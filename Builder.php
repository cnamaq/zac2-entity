<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity;

class Builder implements BuilderInterface
{
    /**
     * @param  string $entity
     * @param  array $rs
     * @return EntityAbstract[]
     */
    public function getEntity($entity, array $rs)
    {
        $result = array();

        foreach ($rs as $data) {
            $result[] = new $entity($data);
        }

        return $result;
    }
    
}
