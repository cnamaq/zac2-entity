<?php
/**
 * Created by PhpStorm.
 * User: fohl
 * Date: 29/04/16
 * Time: 16:41
 */

namespace Zac2\Entity\Manager;


use Zac2\Common\DicAware;
use Zac2\Entity\EntityAbstract;
use Zac2\Filter\Multi\Multi;

class Manager extends DicAware
{
    /**
     * @param $entity
     * @param array|null $params
     * @return EntityAbstract[]
     */
    public function get($entity, array $params = null)
    {
        $filtre = $this->getFiltre($entity, $params);
        return $this->getEntities($entity, $filtre);
    }

    /**
     * @param string $entity
     * @param Multi $filtre
     * @return EntityAbstract[]
     * @throws \Exception
     */
    public function getEntities($entity, Multi $filtre = null)
    {
        $config = $this->getConfig($entity . '.yml', 'Entity');
        $entityManager = $this->getDic()->get($config['entitymanager']);
        if (isset($config['table'])) {
            $entityManager->getDataRequestAdapter()->from($config['table']);
        }
        if (isset($config['join'])) {
            $entityManager->getDateRequestAdapter()->join($config['join'], $config['on'], $config['columns'], 'left');
        }

        return $entityManager->get($config['domainentity'], $filtre);
    }

    /**
     * @param string $entity
     * @param array $params
     * @return Multi
     * @throws \Exception
     */
    protected function getFiltre($entity, array $params = null)
    {
        if (is_null($params)) {
            return null;
        }
        $filtreConfig = $this->getConfig($entity . '.yml', 'FilterMulti');
        $filtre = $this->getDic()->get('filtermulti.factory')->create($filtreConfig);
        $filtre->setValues($params);

        return $filtre;
    }
    
}