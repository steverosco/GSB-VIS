<?php

namespace GSB\gsbBundle\Controller;
require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PdoGsb;

/**
 * Description of FraisComptableController
 *
 * @author kevin
 */
class FraisComptableController extends Controller {

    public function listeVisiteursAccueilComptableAction() {
        $listeVisiteur = $this->getVisiteurs();
        return $this->render('GSBgsbBundle:comptable:accueilComptable.html.twig', array('listeVisiteur' => $listeVisiteur));
        //return new Response("Liste des missions") ;
    }
    
    public function listeFraisVisAction() {
        $form = $this->createFormBuilder()
                ->add('idVis', 'hidden')
                ->getForm();

        $request = Request::createFromGlobals();

        if ($request->getMethod() == 'POST' && $form->handleRequest($request)->isValid()) {
            $idVis = $form['idVis']->getData();
            $pdo = $this->container->get('db1');
        $req = $pdo->query("select "
                . "LigneFraisForfait.idFraisForfait, "
                . "LigneFraisForfait.mois, "
                . "LigneFraisForfait.quantite "
                . "from Visiteur, LigneFraisForfait "
                . "where Visiteur.id = LigneFraisForfait.idVisiteur and Visiteur.id = '$idVis';");
        $listeFraisVis = $req->fetchAll();
//        return $listeFraisVis;
        
//        $listeFraisVis = $this->vuFrais($idVis);
        return $this->render('GSBgsbBundle:comptable:FraisVisComptable.html.twig', array('listeFraisVis' => $listeFraisVis));
        }
        return $this->render('GSBgsbBundle:comptable:accueilComptable.html.twig', array('form' => $form->createView()));
    }

    public function getVisiteurs() {
        $pdo = $this->container->get('db1');
        $req = $pdo->query("select Visiteur.id, "
                . "Visiteur.nom, "
                . "Visiteur.prenom, "
                . "Visiteur.adresse, "
                . "Visiteur.cp, "
                . "Visiteur.ville, "
                . "Visiteur.dateEmbauche "
                . "from Visiteur, Comptable "
                . "where Visiteur.idComptable = Comptable.id "
                . "and Visiteur.idComptable = '" . $session = $this->getRequest()->getSession()->get('id') . "';");
        $listeVisiteur = $req->fetchAll();
        return $listeVisiteur;
    }
    
//    public function vuFrais($idVis){
////        $idVis->getRequest()->get('id');
//        $pdo = $this->container->get('db1');
//        $req = $pdo->query("select "
//                . "LigneFraisForfait.idFraisForfait, "
//                . "LigneFraisForfait.mois, "
//                . "LigneFraisForfait.quantite "
//                . "from Visiteur, LigneFraisForfait "
//                . "where Visiteur.id = LigneFraisForfait.idVisiteur and Visiteur.id = '$idVis';");
//        $listeFraisVis = $req->fetchAll();
//        return $listeFraisVis;
//    }
    
    
//    public function accueilComptableAction() {
//        return $this->render('GSBgsbBundle:comptable:accueilComptable.html.twig');
//    }

}
