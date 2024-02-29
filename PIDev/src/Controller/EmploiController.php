<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EmploiType;
use App\Entity\Emploi;

class EmploiController extends AbstractController
{
    #[Route('/emploi', name: 'app_emploi')]
    public function index(): Response
    {
        return $this->render('emploi/index.html.twig', [
            'controller_name' => 'EmploiController',
        ]);
    }
    #[Route('/addEmploi', name: 'app_addEmploi')]
    public function addEmploi(Request $request, ManagerRegistry $doctrine): Response
    {

        $emploi = new Emploi();
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $doctrine->getManager();
            $em->persist($emploi);
            $em->flush();
            return $this->redirectToRoute('app_addEmploi');

        }
    
        return $this->renderForm("emploi/ajouterEmploi/ajouterEmploi.html.twig", ["form" => $form]);



}
}