<?php
/**
 * @author Denis Fohl
 */

namespace Zac2\Entity\Manager;


use Zac2\Common\DicAware;
use Zac2\Domain\Enseignant;
use Zac2\Filter\Multi\Critere;
use Zac2\Filter\Multi\Multi;

class EnseignantAnnee extends DicAware implements ManagerInterface
{
    
    /**
     * @param Multi $filtre
     * @return array
     */
    public function getIdLst(Multi $filtre)
    {
        return array_keys($this->getMultiOptions($filtre));
    }

    /**
     * @param Multi $filtre
     * @return array
     */
    public function getMultiOptions(Multi $filtre)
    {
        $result = array();

        $entityManager  = $this->getDic()->get('entitymanager.gescicca.requeteur.cachesession');
        $entityManager->getDataRequestAdapter()->from('programme_aqu');
        $data           = $entityManager->getArrayData('programme', $filtre);
        foreach ($data as $row) {
            $result[$row['enseignant_code']] = $row['enseignant_nom'] . ' ' . $row['enseignant_prenom'];
        }
        $entityManager->getDataRequestAdapter()->from('remuneration_forfaitaire_aqu');
        $data           = $entityManager->getArrayData('forfait', $filtre);
        foreach ($data as $row) {
            $result[$row['enseignant_code']] = $row['enseignant_nom'] . ' ' . $row['enseignant_prenom'];
        }
        asort($result);

        return $result;
    }

    /**
     * @param string $entity
     * @param Multi $filtre
     * @return Enseignant[]
     * @throws \Exception
     */
    public function get($entity, Multi $filtre)
    {
        if (!$filtre->hasCritere('annee')) {
            throw new \Exception('critère année manquant');
        }
        if ($filtre->hasCritere('enseignant_code') && !($filtre->getCritere('enseignant_code')->getValue())) {
            $filtre->removeCritere('enseignant_code');
        }
        if (!$filtre->hasCritere('enseignant_code')) {
            $idLst = $this->getIdLst($filtre);
            $critere = new Critere(array(
                'id' => 'enseignant_code',
                'key' => 'enseignant_code',
                'valueFrom' => 'enseignant_code',
                'operator' => 'in',
                'value' => $idLst,
            ));
            $filtre->addCritere($critere);
        }
        $entityManager = $this->getDic()->get('entitymanager.gescicca.requeteur');
        $entityManager->getDataRequestAdapter()->from('enseignant_aqu');

        $filtre->removeCritere('annee');
        return $entityManager->get('Zac2\Domain\Enseignant', $filtre);
    }

}
