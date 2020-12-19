<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\CreationVilleType;
use App\Form\VilleSearchType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class VilleController extends AbstractController
{
    /**
     * @Route("/villes",name="main_villes")
     */
    public function ville(VilleRepository $villeRepository,Request $request){

        $villeForm=$this->createForm(VilleSearchType::class);
        $villeForm->handleRequest($request);
        $villeRepository=$this->getDoctrine()->getRepository(Ville::class);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $value=$villeForm->getData();
            $villes = $villeRepository->searchVille($value);
        }
        else
        {
            $villes=$villeRepository->findAll();
        }

        return $this->render('admin/villes.html.twig',[
            'villes'=>$villes,
            'villeForm'=>$villeForm->createView()
        ]);
    }

//    /**
//     * @Route("/villes/search",name="ville_search")
//     */
//    public function searchVille(VilleRepository $villeRepository,Request $request){
//        $villeRepository=$this->getDoctrine()->getRepository(Ville::class);
//        $villeForm->handleRequest($request);
//        $villes=$villeRepository->findBy($request);
//        return $this->render('admin/villes.html.twig',[
//            'villes'=>$villes
//        ]);
//    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/createville",name="ville_create")
     */
    public function createVille(Request $request, EntityManagerInterface $em){
        $ville=new Ville();
        $villeForm=$this->createForm(CreationVilleType::class,$ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();
            $this->addFlash("success", "La ville a été ajoutée avec succès");
            return $this->redirectToRoute("main_villes");
        }
        return $this->render('admin/creationville.html.twig', [
            'villeForm'=>$villeForm->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/editville{id}",name="ville_edit", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function editVille(Request $request, EntityManagerInterface $em,$id){
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville=$villeRepo->find($id);
        $villeForm=$this->createForm(CreationVilleType::class,$ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();
            $this->addFlash("success", "La ville a été modifiée avec succès");
            return $this->redirectToRoute("main_villes");
        }
        return $this->render('admin/editville.html.twig',[
            'villeForm'=>$villeForm->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/deleteville{id}",name="ville_delete", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function supprimerVille(EntityManagerInterface $em,$id){
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville=$villeRepo->find($id);
            $em->remove($ville);
            $em->flush();
            $this->addFlash("success", "La ville a été supprimée avec succès");
            return $this->redirectToRoute("main_villes");

    }


}
