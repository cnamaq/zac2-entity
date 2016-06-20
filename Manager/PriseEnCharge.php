<?php
/**
 * Created by PhpStorm.
 * User: fohl
 * Date: 27/05/16
 * Time: 11:43
 */

namespace Zac2\Entity\Manager;


use Zac2\Common\DicAware;
use Zac2\Data\Request\SqlString;
use Zac2\Filter\Multi\Multi;

class PriseEnCharge extends DicAware implements ManagerInterface
{
    public function get($entity, Multi $filtre)
    {
        $em = $this->getDic()->get('entitymanager.gescicca.requeteur');
        $dataRequest = new SqlString();
        $dataRequest->setSql("SELECT p.*, 
                i.inscription_date, i.centre_attachement_libelle,
                iu.inscription_unite_date_creation, iu.modalite, 
                u.unite_code, u.unite_nb_heure, u.unite_ects,
                uo.regroupement_programme_code, uo.regroupement_programme_libelle
            FROM prise_en_charge_aqu as p
            LEFT JOIN inscription_aqu as i ON i.centre_code=p.centre_code 
                AND i.auditeur_numero = p.auditeur_numero 
                AND i.annee = p.annee
            LEFT JOIN inscription_unite_aqu AS iu ON iu.centre_code = p.centre_code
                AND iu.unite_numero = p.unite_numero
                AND iu.auditeur_numero = p.auditeur_numero
                AND iu.annee = p.annee
                AND iu.semestre_code = p.semestre_code
            LEFT JOIN unite_aqu AS u ON u.unite_numero = p.unite_numero
            LEFT JOIN unite_ouverte_aqu as uo ON uo.centre_code = p.centre_code 
                AND uo.annee = p.annee 
                AND uo.unite_numero = p.unite_numero
                AND uo.semestre_code = p.semestre_code
                AND uo.groupe_code = iu.groupe_code
            WHERE p.annee={$filtre->getCritere('annee')->getValue()}");
        $em->setDataRequestAdapter($dataRequest);

        return $em->get('\Zac2\Domain\PriseEnCharge', $filtre);
    }

}
