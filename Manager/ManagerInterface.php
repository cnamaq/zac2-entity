<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity\Manager;


use Zac2\Filter\Multi\Multi;

interface ManagerInterface
{
    /**
     * @param string $entity
     * @param Multi $filtre
     * @return array
     */
    public function get($entity, Multi $filtre);
}