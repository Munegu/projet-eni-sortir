<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_accueil")
     */
    public function accueil(SortieRepository $sortieRepository)
    {
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepository->findBy([],['dateLimiteInscription'=>'DESC'],10);
        return $this->render('main/accueil.html.twig', [
            'sorties'=>$sortie
        ]);
    }








}
