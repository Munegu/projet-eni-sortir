<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\CreationLieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
//    /**
//     * @Route("/sortie/create/lieu", name="lieu_creation")
//     */
//    public function creationLieu(Request $request, EntityManagerInterface $em)
//    {
//
//        $lieu= new Lieu();
//        $lieuForm = $this->createForm(CreationLieuType::class, $lieu);
//        $lieuForm->handleRequest($request);
//        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {
//
//                $em->persist($lieu);
//                $em->flush();
//                $this->addFlash("success", "Lieu enregistrée avec succès");
//                return $this->redirectToRoute("sortie_create");
//
//        }
//        return $this->render('sortie/create.html.twig',[
//            'lieuForm'=>$lieuForm->createView()
//        ]);
//    }
}
