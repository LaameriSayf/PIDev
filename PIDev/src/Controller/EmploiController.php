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
            //check if the rendez vous exists
            $existingEmplois = $em->getRepository(Emploi::class)->findOneBy([
                'start' => $emploi->getStart(),
                'end' => $emploi->getEnd(),
            ]);
    
            if ($existingEmplois !== null) {
                $this->addFlash('error', 'Rendez-vous déjà existant.');
                return $this->redirectToRoute('app_addRendezvous');
       
    }
    
            $em->persist($emploi);
            $em->flush();
            $this->addFlash('success', 'Emplois ajouté avec succès.');
            return $this->redirectToRoute('app_addEmploi');

        }
    
        return $this->renderForm("emploi/ajouterEmploi/ajouterEmploi.html.twig", ["form" => $form]);



}
#[Route('/editEmploi/{id}/edit', name: 'app_editEmploi', methods: ['PUT'])]
public function editEmploi(Emploi $emploi, Request $request,ManagerRegistry $doctrine)
{
    $data = json_decode($request->getContent(), true); // Utilisez json_decode

    if (
        isset($data['id']) && !empty($data['id']) &&
        isset($data['titre']) && !empty($data['titre']) &&
        isset($data['start']) && !empty($data['start']) &&
        isset($data['end']) && !empty($data['end']) &&
        isset($data['description']) && !empty($data['description'])

    ) { $code = 200;
        if (!$emploi) {

        $emploi = new Emploi;
        $code = 201;

    }
       
        $emploi->setTitre($data['titre']);
        $emploi->setStart(new \DateTime($data['start']));
        $emploi->setEnd(new \DateTime($data['end']));
        $emploi->setDescription($data['description']);

        // Effectuer la mise à jour dans la base de données, par exemple avec Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($emploi);
        $entityManager->flush();

        return new Response('ok',$code);


    }else {
            return new Response('donnees incompletes',404);
        


    }



    








}
}