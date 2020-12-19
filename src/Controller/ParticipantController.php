<?php

namespace App\Controller;

use App\Entity\Participant;

use App\Form\CreationCompteType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ParticipantController extends AbstractController
{

    /**
     * Créer un nouvel utilisateur
     * @Route("/register", name="participant_register")
     */
    public function register(Request $request, EntityManagerInterface $em,
                             UserPasswordEncoderInterface $encoder)
    {


        $participant= new Participant();
        $registerForm = $this->createForm(CreationCompteType::class,$participant);
        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $participant->setAdministrateur(false);
            $password = $encoder->encodePassword($participant, $participant->getPassword());
            $participant->setPassword($password);
            $em->persist($participant);
            $em->flush();
            $this->addFlash("success", "Nouveau compte créé avec succès");
            return $this->redirectToRoute("app_login");
        }
        return $this->render('participant/register.html.twig', [
            'registerForm' => $registerForm->createView(),
        ]);
    }


    /**
     * Modifier le profil
     * @Route("/editprofil",name="participant_editprofil", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function modifierProfil(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, Request $request){
        $participant = $em->getRepository(Participant::class)->findOneBy([
            "username"=>$this->getUser()->getUsername()
        ]);
        if ($participant==null) {
            throw $this->createNotFoundException("Participant inconnu");
        }
        $participantForm = $this->createForm(CreationCompteType::class, $participant);
        $participantForm->handleRequest($request);
        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            $participant->setAdministrateur(false);
            $participant->setActif(true);
            $password = $encoder->encodePassword($participant, $participant->getPassword());
            $participant->setPassword($password);
            $em->persist($participant);
            $em->flush();
            $this->addFlash("success", "Votre profil a bien été modifié");
            // redirection
            return $this->redirectToRoute("participant_editprofil", [
                'id' => $participant->getId()
            ]);
        }
        return $this->render('participant/editprofil.html.twig',[
            'registerForm' => $participantForm->createView()
        ]);
    }


    /**
     * @Route("/viewprofil{id}",name="participant_viewprofil", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function afficherProfil($id){

        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant=$participantRepo->find($id);
        if ($participant == null){
            throw $this->createNotFoundException("Participant incconnu");
        }
        return $this->render('participant/viewprofil.html.twig',[
            'partipant'=>$participant
        ]);
    }

}
