<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dossiermedical;
use App\Form\DossierType;
use App\Entity\Patient;
use App\Repository\OrdonnanceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\DossiermedicalRepository;
use App\Repository\DossiermedicalRepository as RepositoryDossiermedicalRepository;
use Container3bVtg3K\getDossiermedicalRepositoryService;

class DossierController extends AbstractController
{
    #[Route('/dossier', name: 'app_dossier')]
    public function index(): Response
    {
        return $this->render('dossier/index.html.twig', [
            'controller_name' => 'DossierController',
        ]);
    }
    #[Route('/addDossier', name: 'add_dossier')]
    public function addDossier(Request $req,ManagerRegistry $doctrine,):Response
    { 
    $dossiers=new Dossiermedical();
    $form=$this->createForm(DossierType::class,$dossiers);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()){
        //$entityManager = $doctrine->getManager();
       // $patient_id = $req->request->get('patient_id');
        //$patient = $entityManager->getRepository(Patient::class)->find($patient_id);

        
        $em=$doctrine->getManager();
        //$dossiers->setPatient($patient);
        $em->persist($dossiers);
        $em->flush(); 
        return $this->redirectToRoute('show_dossier');
    }
    
    return $this->renderForm("dossier/add1.html.twig", ["form"=>$form]);

}
    


     
    #[Route('/showDossier', name: 'show_dossier')]
    public function show(ManagerRegistry $doctrine): Response
    {
    $repo=$doctrine->getRepository(Dossiermedical::class);
    $dossiers=$repo->findAll();
    return $this->render('dossier/show1.html.twig', ['listDossiers'=>$dossiers]);
    }
    
     
     #[Route('/editDossier/{id}', name: 'edit_Dossier')]
     public function edit($id, RepositoryDossiermedicalRepository $repository,ManagerRegistry $doctrine, Request $request)
    {
     $dossier = $repository->find($id);
     $form = $this->createForm(DossierType::class, $dossier);
     $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();
        $em->flush(); //la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
        return $this->redirectToRoute("show_dossier");
    }

     return $this->render('dossier/edit1.html.twig', [
        'form' => $form->createView(),
    ]);
}


    

   
}
