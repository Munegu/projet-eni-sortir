<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\CreationSiteType;
use App\Form\SearchSiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site",name="main_sites")
     */
    public function ville(SiteRepository $siteRepository, Request $request){
        $siteForm=$this->createForm(SearchSiteType::class);
        $siteForm->handleRequest($request);
        $siteRepository=$this->getDoctrine()->getRepository(Site::class);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $value=$siteForm->getData();
            $sites = $siteRepository->searchSite($value);
        }
        else
        {
            $sites=$siteRepository->findAll();
        }
        return $this->render('admin/sites.html.twig',[
            'sites'=>$sites,
            'siteForm'=>$siteForm->createView()
        ]);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/createsite",name="site_create")
     */
    public function createSite(Request $request, EntityManagerInterface $em){
        $site=new Site();
        $siteForm=$this->createForm(CreationSiteType::class,$site);
        $siteForm->handleRequest($request);
        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $em->persist($site);
            $em->flush();
            $this->addFlash("success", "Le site a été ajouté avec succès");
            return $this->redirectToRoute("main_sites");
        }
        return $this->render('admin/creationsite.html.twig', [
            'siteForm'=>$siteForm->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/editsite{id}",name="site_edit", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function editSite(Request $request, EntityManagerInterface $em,$id){
        $siteRepo = $this->getDoctrine()->getRepository(Site::class);
        $site=$siteRepo->find($id);
        $siteForm=$this->createForm(CreationSiteType::class,$site);
        $siteForm->handleRequest($request);
        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $em->persist($site);
            $em->flush();
            $this->addFlash("success", "Le site a été modifié avec succès");
            return $this->redirectToRoute("main_sites");
        }
        return $this->render('admin/editsite.html.twig',[
            'siteForm'=>$siteForm->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/deletesite{id}",name="site_delete", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function supprimerSite(EntityManagerInterface $em,$id){
        $siteRepo = $this->getDoctrine()->getRepository(Site::class);
        $site=$siteRepo->find($id);
        $em->remove($site);
        $em->flush();
        $this->addFlash("success", "Le site a été supprimé avec succès");
        return $this->redirectToRoute("main_sites");

    }
}
