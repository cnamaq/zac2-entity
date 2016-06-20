<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity;


class ManagerFactory
{
    /**
     * @param  array $config
     * @return Manager
     */
    public function create(array $config)
    {
        $entityManager = new Manager();

        return $entityManager;
    }
}