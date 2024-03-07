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
        
        $em=$doctrine->getManager();
        // Récupérer l'ID du patient depuis la requête
       // $patientId = $req->get('patient_id');
        // Récupérer le patient depuis la base de données
       // $patient = $em->getRepository(Patient::class)->find($patientId);
        //if (!$patient) {
           // throw $this->createNotFoundException('Patient non trouvé pour l\'ID '.$patientId);
      //  }
        // Associer le dossier médical au patient
       // $dossiers->setPatient($patient);

        $em->persist($dossiers);
        $em->flush(); 
        return $this->redirectToRoute('show_dossier');
    }
    
    return $this->renderForm("dossier/add1.html.twig", ["form"=>$form]);

}

    
     
    #[Route('/showDossier', name: 'show_dossier')]
    public function show(ManagerRegistry $doctrine,Request $req,DossiermedicalRepository $repo): Response
    {
    // Récupérer la date depuis la requête
    $date = $req->query->get('date');   
     // Vérifier si une date a été fournie dans la requête
     if ($date) {
        // Convertir la date en objet DateTime si nécessaire
        $dateObj = new DateTime($date);

        // Récupérer les dossiers médicaux filtrés par date
        $dossiers = $repo->findByDate($dateObj);}
        else {
     $dossiers = $repo->findBy([], ['DateCreation' => 'DESC']);
    
   /// $repo = $doctrine->getRepository(Dossiermedical::class);
   // $dossiers = $repo->findAll();
    return $this->render('dossier/show1.html.twig', ['listDossiers'=>$dossiers]);

     }
    
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
   //#[Route('/searchDossier', name: 'search_dossier')]
   // public function searchDossier(Request $request, DossiermedicalRepository $dossierRepository): Response
    //{
    //$numDossier = $request->query->get('numdossier');

    // Utilisez le repository pour rechercher les dossiers médicaux par numéro de dossier
    //$dossiers = $dossierRepository->findByNumdossier($numDossier);

    // Retournez une réponse, par exemple, au format JSON ou à une vue Twig
  //  return $this->render('show1.html.twig', [
       // 'dossiers' => $dossiers,
    //]);
     
   // #[Route('/creerDossier/{patientId}', name: 'creer_dossier')]
    //public function addDossierForPatient($patientId, DossiermedicalRepository $dossierRepo): Response
 //  {
       //$dossier = $dossierRepo->createDossierForPatient($patientId);

       ////if (!$dossier) {
           // Handle the case where the dossier could not be created (e.g., patient not found).
          /// throw $this->createNotFoundException('Patient not found for ID ' . $patientId);
       //}
////
       // Redirect to a route where you can display the dossier or confirm creation.
      // return $this->redirectToRoute('show_dossier', ['id' => $dossier->getId()]);
  // }
}





    

   

