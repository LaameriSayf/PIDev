<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordonnance;
use App\Entity\Dossiermedical;
use App\Form\DossierType;
use App\Form\DossierMedicalType;
use App\Entity\Patient;
use App\Repository\OrdonnanceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DossiermedicalRepository;
use DateTime;
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
    public function addDossier(Request $req,ManagerRegistry $doctrine):Response
    { 
    $dateActuelle = new DateTime();
    $dossiers=new Dossiermedical();
    $dossiers ->setDateCreation($dateActuelle);
    $form=$this->createForm(DossierType::class,$dossiers);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()){
        
        //$entityManager = $doctrine->getManager();
        //$ordonnance_id = $req->request->get('ordonnance_id');
        //$ordonnance = $entityManager->getRepository(Patient::class)->find($ordonnance_id);
 
        $em=$doctrine->getManager();
         // Récupérer les ordonnances associées au dossier
        // $ordonnances = $form->get('ordonnance')->getData();

         // Associer les ordonnances sélectionnées au dossier médical
        // foreach ($ordonnances as $ordonnance) {
            // $dossiers->addOrdonnance($ordonnance);
        // }

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
     $Form = $this->createForm(DossierMedicalType::class, $dossier);
     $Form->handleRequest($request);
     if ($Form->isSubmitted() && $Form->isValid()) {
        $em=$doctrine->getManager();
        $ordonnances = $Form->get('ordonnance')->getData();
        foreach ($ordonnances as $ordonnance) {
            $ordonnance->setDossierMedical($dossier); // Assurez-vous que la relation est correctement configurée
            $em->persist($ordonnance);
        }
        $em->flush($dossier); //la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
        return $this->redirectToRoute("show_dossier");
    }

     return $this->render('dossier/edit1.html.twig', [
        'Form' => $Form->createView(),
    ]);
}




    

   
}
