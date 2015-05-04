<?php

namespace GSB\gsbBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FicheFraisRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FicheFraisRepository extends EntityRepository {
    
        public function findByMois($idVisiteur) {
        $queryBuilder = $this->createQueryBuilder('FicheFrais') ;
        $queryBuilder-> where('FicheFrais.idvisiteur = :idV') 
                        ->setParameter('idV', $idVisiteur)
                     ->add('orderBy','FicheFrais.mois DESC');
        $result = $queryBuilder->getQuery()->getResult() ;
        if ($result) {
            $lesMois = array() ;
            foreach($result as $ligne) {
                $mois = $ligne->getMois() ;
                $numAnnee = substr($mois,0,4);
                $numMois =substr( $mois,4,2);
                $lesMois[$mois]=array("mois"=>$mois,
                                      "numAnnee"  => $numAnnee,
                                      "numMois"  => $numMois
                                     );
            }
            return $lesMois ;
        }
        else {
            return NULL ;
        }
    }
}
