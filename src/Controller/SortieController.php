<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\AnnulationSortieType;
use App\Form\CreationLieuType;
use App\Form\CreationSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SortieController
 * @package App\Controller
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/detail{id}", name="sortie_detail", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function detailSortie($id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        if ($sortie == null){
            throw $this->createNotFoundException("Sortie incconnue");
        }
        return $this->render('sortie/detail.html.twig', [
            'sortie'=>$sortie
        ]);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/create",name="sortie_create")
     */
    public function creerSortie(Request $request, EntityManagerInterface $em){
        $participant = $em->getRepository(Participant::class)->findOneBy([
            "username"=>$this->getUser()->getUsername()
        ]);
        $etatcree = $em->getRepository(Etat::class)->find(7);
        $etatpublie = $em->getRepository(Etat::class)->find(8);
        $sortie= new Sortie();
        $sortieForm = $this->createForm(CreationSortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        $lieu= new Lieu();
        $lieuForm = $this->createForm(CreationLieuType::class, $lieu);
        $lieuForm->handleRequest($request);
        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {

            $em->persist($lieu);
            $em->flush();
            $this->addFlash("success", "Lieu enregistrée avec succès");
//            return $this->redirectToRoute("sortie_create");

        }

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $sortie->setOrganisateur($participant);
            $sortie->setSiteOrganisateur($participant->getSiteRattache());

            if ($sortieForm->getClickedButton() && 'enregistrer' === $sortieForm->getClickedButton()->getName()) {
                $sortie->setEtat($etatcree);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash("success", "Sortie enregistrée avec succès");
                return $this->redirectToRoute("main_accueil");

            }


            elseif  ($sortieForm->getClickedButton() === $sortieForm->get('publier')) {
                $sortie->setEtat($etatpublie);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash("success", "Sortie publiée avec succès");
                return $this->redirectToRoute("main_accueil");

            }

        }
        return $this->render('sortie/create.html.twig',[
            'sortieForm'=>$sortieForm->createView(),
            'lieuForm'=>$lieuForm->createView()
        ]);
    }

    /**
     * @Route("/inscription{id}",name="sortie_inscription", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function inscriptionSortie($id, EntityManagerInterface $em){
        $now=new \DateTime();
        $participant = $em->getRepository(Participant::class)->findOneBy([
            "username"=>$this->getUser()->getUsername()]);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        if ($sortie->getDateLimiteInscription()> $now ){
            $sortie->addInscrit($participant);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash("success", "Vous êtes bien inscrit à l'évenement");
        }
        else{
            $this->addFlash("warning", "La date limite d'inscription est passé");

        }


        return $this->redirectToRoute('main_accueil');
    }


    /**
     * @Route("/desinscription{id}",name="sortie_desinscription", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function desinscriptionSortie($id, EntityManagerInterface $em){
        $participant = $em->getRepository(Participant::class)->findOneBy([
            "username"=>$this->getUser()->getUsername()]);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        $sortie->removeInscrit($participant);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash("success", "Vous êtes bien désinscrit de l'évenement");

        return $this->redirectToRoute('main_accueil');
    }

    /**
     * @Route("/publier{id}",name="sortie_publier", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function publierSortie($id, EntityManagerInterface $em){
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        $etatpublie = $em->getRepository(Etat::class)->find(8);
        $sortie->setEtat($etatpublie);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash("success", "L'événement a bien été publié");

        return $this->redirectToRoute('main_accueil');
    }



    /**
     * @Route("/edit{id}", name="sortie_modif", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function modifierSortie(Request $request, EntityManagerInterface $em,$id){
        $etatcree = $em->getRepository(Etat::class)->find(7);
        $etatpublie = $em->getRepository(Etat::class)->find(8);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        $sortieForm = $this->createForm(CreationSortieType::class, $sortie);
        $sortieForm->handleRequest($request);


        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {


            if ($sortieForm->getClickedButton() && 'enregistrer' === $sortieForm->getClickedButton()->getName()) {
                $sortie->setEtat($etatcree);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash("success", "Sortie enregistrée avec succès");
                return $this->redirectToRoute("main_accueil");

            }


            elseif  ($sortieForm->getClickedButton() === $sortieForm->get('publier')) {
                $sortie->setEtat($etatpublie);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash("success", "Sortie publiée avec succès");
                return $this->redirectToRoute("main_accueil");

            }

        }
        return $this->render('sortie/modif.html.twig',[
            'sortieForm'=>$sortieForm->createView(),
            'sortie'=>$sortie
        ]);
    }

    /**
     * @Route("/cancel{id}",name="sortie_cancel", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function annulerSortie($id, EntityManagerInterface $em,Request $request){
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        if ($sortie==null) {
            throw $this->createNotFoundException("Sortie inconnue");
        }
        $sortieForm = $this->createForm(AnnulationSortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        $etatannule = $em->getRepository(Etat::class)->find(12);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $sortie->setEtat($etatannule);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash("success", "Sortie annulée avec succès");
            return $this->redirectToRoute("main_accueil");
        }
        return $this->render('sortie/cancel.html.twig',[
            'sortieForm'=>$sortieForm->createView(),
            'sortie'=>$sortie
        ]);
    }
}




