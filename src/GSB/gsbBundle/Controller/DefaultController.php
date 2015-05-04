<?php

namespace GSB\gsbBundle\Controller;

require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {   // ok

    public function indexAction() {
        $form = $this->createFormBuilder()
                ->add('login', 'text', array('attr' => array('class' => 'form-control')))
                ->add('mdp', 'password', array('attr' => array('class' => 'form-control')))
                ->add('profil', 'choice', array('choices' => array('Comptable', 'Délégué', 'Responsable', 'Visiteur'),
                    'attr' => array('class' => 'form-control')), array('preferred_choices' => array('Visiteur')))
                ->getForm();
        // Dans la liste, on affiche Visiteurs en premier
        $form->get('profil')->setData(3);

        $request = Request::createFromGlobals();

        if ($request->getMethod() == 'POST' && $form->handleRequest($request)->isValid()) {
            $session = $this->getRequest()->getSession();
            $login = $form['login']->getData();
            $mdp = $form['mdp']->getData();
            $profil = $form['profil']->getData();
            switch ($profil) {
                case 0 :
                    $profil = "Comptable";
                    // Récupérer l'entité avec Doctrine - 
//                    $repository = $this->getDoctrine()->getManager()
//                            ->getRepository('GSBgsbBundle:Comptable');
//
//                    // Fonction définie dans VisiteurRepository.php
//                    $comptable = $repository
//                            ->findByLoginAndMdp($login, $mdp);
                    $res = $this->verifierConnexion($login,$mdp,$profil) ;
                    $session = $this->getRequest()->getSession() ;
                    if ($res === NULL) {
                        $message = $this->get('session')->getFlashBag()
                                ->add('erreurLogMdp', "Login 
                                                   et/ou mot de passe 
                                                   erroné(s)");

                        return $this->redirect('index');
                    } else {
                        $session->set('id', $res['id']);
                        $session->set('nom',$res['nom']);
                        $session->set('prenom',$res['prenom']) ;
//                        // récupérer les informations sur le visiteur connecté
//                        $nomC = $comptable->getNom();
//                        $prenomC = $comptable->getPrenom();
//                        $idC = $comptable->getId();
//
//                        // sauvegarder les infos du visiteur dans une variable 
//                        // de session pour usage dans d'autres fonctions
//
//                        $session->set('nom', $nomC);
//                        $session->set('prenom', $prenomC);
//                        $session->set('id', $idC);
                        $url = $this->generateUrl('gsb_homepage_comptable', array('id'=>$session->get('id'), 'nom'=>$session->get('nom'), 'prenom'=>$session->get('prenom')));
                        return $this->redirect($url);
//                        return $this->render('GSBgsbBundle:comptable:accueilComptable.html.twig', array('nomC' => $nomC, 'prenomC' => $prenomC));
                    }
                    break;
                case 1 :
                    $profil = "delegue";
                    break;
                case 2 :
                    $profil = "Responsable";
                    break;
                case 3 :
                    $profil = "Visiteur";
                    // Récupérer l'entité avec Doctrine - 
//                    $repository = $this->getDoctrine()->getManager()
//                            ->getRepository('GSBgsbBundle:Visiteur');
//
//                    // Fonction définie dans VisiteurRepository.php
//                    $visiteur = $repository
//                            ->findByLoginAndMdp($login, $mdp);
                    $res = $this->verifierConnexion($login,$mdp,$profil) ;
                    $session = $this->getRequest()->getSession() ;
                    if ($res === NULL) {
                        $message = $this->get('session')->getFlashBag()
                                ->add('erreurLogMdp', "Login 
                                                   et/ou mot de passe 
                                                   erroné(s)");

                        return $this->redirect('index');
                    } else {
                        $session->set('id', $res['id']);
                        $session->set('nom',$res['nom']);
                        $session->set('prenom',$res['prenom']) ;
//                        return $this->render('KHbiBundle:Artisan:accueilArtisan.html.twig', array('id'=>$session->get('idArtisan'), 'nom'=>$res['nomArtisan'], 'prenom'=>$res['prenomArtisan'])) ;
                    
//                        // récupérer les informations sur le visiteur connecté
//                        $nom = $visiteur->getNom();
//                        $prenom = $visiteur->getPrenom();
//                        $id = $visiteur->getId();
//
//                        // sauvegarder les infos du visiteur dans une variable 
//                        // de session pour usage dans d'autres fonctions
//
//                        $session->set('nom', $nom);
//                        $session->set('prenom', $prenom);
//                        $session->set('id', $id);
                        $url = $this->generateUrl('gsb_homepage_visiteurs', array('id'=>$session->get('id'), 'nom'=>$session->get('nom'), 'prenom'=>$session->get('prenom')));
                        return $this->redirect($url);
                        
//                        return $this->render('GSBgsbBundle:Visiteurs:accueilVisiteur.html.twig', array('id'=>$session->get('id'), 'nom'=>$session->get('nom'), 'prenom'=>$session->get('prenom')));
                    }
                    break;
            }
        }
        return $this->render('GSBgsbBundle:Default:formConnexion.html.twig', array('form' => $form->createView()));
    }

    
    public function verifierConnexion($login,$mdp,$profil){
        $pdo = $this->container->get('db1');
       $ligne=null;
        if ($profil == "Visiteur") {
            $stmt = $pdo->prepare("select id, nom, prenom 
                                   from Visiteur 
                                   where login = :log 
                                   and mdp = :mdp ") ;
            
            $stmt->execute(array('log'=>$login,'mdp'=>$mdp)) ;
            $ligne = $stmt->fetch();
        }
        else if ($profil == "Comptable") {
            $stmt = $pdo->prepare("select id, nom, prenom
                                    from Comptable
                                    where login = :log 
                                    and mdp = :mdp");
            
            $stmt->execute(array('log'=>$login, 'mdp'=>$mdp)) ;
            $ligne = $stmt->fetch();
        }
	return $ligne;
    }
    
    
    public function seDeconnecterAction() {
        $session = $this->getRequest()->getSession();
        $url = null;
        if($session->get('profil')== 'Visiteur'){
            $session->clear();
            $url = $this->generateUrl('gsb_homepage', array('profil'=>'Visiteur'));
        }
        else if($session->get('profil')== 'Comptable'){
            $session->clear();
            $url = $this->generateUrl('gsb_homepage', array('profil'=>'Comptable'));
        }
//        $session->clear();
//        $url = $this->generateUrl('gsb_homepage');
        return $this->redirect($url);
    }

}

?>