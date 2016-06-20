<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineOrmFactory
{
    /**
     * @param array $params
     * @return EntityManager
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(array $params)
    {
        $isDevMode = ($params['devMode']) ? $params['devMode'] : true;
        $config = Setup::createAnnotationMetadataConfiguration(array("/../Zac2/Domain"), $isDevMode);
        $configDb = new \Doctrine\DBAL\Configuration();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($params, $configDb);

        return EntityManager::create($conn, $config);
    }
}