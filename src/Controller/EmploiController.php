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
public function editEmploi(Emploi $emploi, Request $request){

$donnes = json_encode($request->getContent());
if(
    isset($donnes->id) && !empty($donnes->id)  &&
    isset($donnes->titre) && !empty($donnes->titre)  &&
    isset($donnes->start) && !empty($donnes->start)  &&
    isset($donnes->end) && !empty($donnes->end)  &&
    isset($donnes->description) && !empty($donnes->description)  
    ){
        $code = 200;
        if (!$emploi){
            $emploi = new Emploi;
            $code = 201;
           
        }
        $emploi->setTitre($donnes->titre);
        $emploi->setDescription($donnes->description);
        $emploi->setStart($donnes->start);
        $emploi->setEnd($donnes->end);
        $em = $this->getDoctrine() -> getManager();
        $em -> persist($emploi);
        $em->flush();
        return new Response('Done',$code);


    }else{
        return new Response('Donnes incompletes',404);
    }

}
