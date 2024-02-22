<?php

namespace App\Controller;

use App\Entity\Patient;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; // Correction ici
use App\Form\RegistrationType;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'signin')]
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }

    #[Route('/register', name:'app_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response // Correction ici
    {
        $patient = new Patient();
        $form = $this->createForm(RegistrationType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe
            $patient->setPassword($passwordEncoder->encodePassword($patient, $form->get('plainPassword')->getData()));

            // Persister l'utilisateur
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patient);
            $entityManager->flush();

            // Rediriger l'utilisateur après l'inscription
            return $this->redirectToRoute('app_register');
        }

        return $this->render('inscription/registration.html.twig', [
            "myForm" => $form->createView(),
        ]);
    }
}
