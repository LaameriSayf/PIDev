<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordonnance;
use App\Form\OrdonnanceType;
use App\Repository\OrdonnanceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
//use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\Patient;
use App\Entity\DossierMedical;
use App\Repository\DossiermedicalRepository;
use Proxies\__CG__\App\Entity\Dossiermedical as EntityDossiermedical;

class OrdonnanceController extends AbstractController
{
    #[Route('/ordonnance', name: 'app_ordonnance')]
    public function index(): Response
    {
        return $this->render('ordonnance/index.html.twig', [
            'controller_name' => 'OrdonnanceController',
        ]);
    }
    #[Route('/addOrdonnance', name: 'add_ordonnance')]
    public function addOrdonnance(Request $req,ManagerRegistry $doctrine):Response
    { 
    $dateActuelle = new DateTime();
    $ordonnance=new Ordonnance();
    $ordonnance ->setDateprescription($dateActuelle);
   
    // Récupérer l'instance du patient
    //$patient = $doctrine->getRepository(Patient::class)->find($idpatient);
    
    // Associer l'ordonnance au patient
    //$ordonnance->setIdpatient(null);
               
    $form=$this->createForm(OrdonnanceType::class,$ordonnance);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()){
       
        
        //$ordonnance->setIdpatient($patient); 
        $em=$doctrine->getManager();
        $em->persist($ordonnance);
        $em->flush(); 
        return $this->redirectToRoute('show_dossier');
    }
    
    return $this->renderForm("ordonnance/add.html.twig", ["form"=>$form]);
    }
    
    #[Route('/showOrdonnance', name: 'ordonnance_show')]
    public function show(ManagerRegistry $doctrine,OrdonnanceRepository $repo,Request $req): Response
    {
    // Récupérer la date depuis la requête
    $date = $req->query->get('date');
    // Vérifier si une date a été fournie dans la requête
    if ($date) {
        // Convertir la date en objet DateTime si nécessaire
        $dateObj = new DateTime($date);

        // Récupérer les ordonnances filtrés par date
        $ordonnance = $repo->findByDate($dateObj);}
        else {
            $ordonnance= $repo->findBy([], ['dateprescription' => 'DESC']);
  
    //$repo=$doctrine->getRepository(Ordonnance::class);
   // $ordonnance=$repo->findAll();

    return $this->render('ordonnance/show.html.twig', ['listOrdonnances'=>$ordonnance]);
    }

    
}/*
#[Route('/showOrdonnance/{id}', name: 'ordonnancedetail_show')]
    public function showdetail(ManagerRegistry $doctrine,OrdonnanceRepository $repo,Request $req,$id,DossiermedicalRepository $rep): Response
    {
        $dossier= $repo->find($id);
        $ordonnance=$dossier->getOrdonnance();
    // Récupérer la date depuis la requête
    $date = $req->query->get('date');
    // Vérifier si une date a été fournie dans la requête
    if ($date) {
        // Convertir la date en objet DateTime si nécessaire
        $dateObj = new DateTime($date);

        // Récupérer les ordonnances filtrés par date
        $ordonnance = $repo->findByDate($dateObj);}
        else {
            $ordonnance= $repo->findBy([], ['dateprescription' => 'DESC']);
  
    //$repo=$doctrine->getRepository(Ordonnance::class);
   // $ordonnance=$repo->findAll();

    return $this->render('ordonnance/show.html.twig', ['listOrdonnances'=>$ordonnance]);
    }
}*/
#[Route('/showOrdonnance/{id}', name: 'ordonnancedetail_show')]
public function showdetail(ManagerRegistry $doctrine,OrdonnanceRepository $repo,Request $req,$id,DossiermedicalRepository $rep): Response {
    // Find the dossier by its ID
    $dossier = $rep->find($id);

    // Retrieve the prescription from the dossier
    //$ordonnance = $repo->findBy(['dossiermedical' => $dossier]);

    // Retrieve the date from the request
    $date = $req->query->get('date');

    // Check if a date is provided in the request
    if ($date) {
        // Convert the date string into a DateTime object
        $dateObj = new DateTime($date);
    
        // Fetch prescriptions filtered by date and dossier
        $ordonnance = $repo->findBy(['dateprescription' => $dateObj, 'dossiermedical' => $dossier], ['dateprescription' => 'DESC']);
    } else {
        // If no date provided, fetch all prescriptions sorted by date for the given dossier
        $ordonnance = $repo->findBy(['dossiermedical' => $dossier], ['dateprescription' => 'DESC']);
    }

    // Render the view with the retrieved prescriptions
    return $this->render('ordonnance/show.html.twig', ['listOrdonnances' => $ordonnance]);
}


    #[Route('/ordonnance/edit/{id}', name: 'ordonnance_edit')]
    public function edit(OrdonnanceRepository $repository, $id, Request $request,ManagerRegistry $doctrine)
    {
   
    $ordonnance= $repository->find($id);
    $form = $this->createForm(OrdonnanceType::class, $ordonnance);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em =$doctrine->getManager(); 
        $em->flush();
        return $this->redirectToRoute("ordonnance_show");
    }

    return $this->render('ordonnance/edit.html.twig', [
        'form' => $form->createView(),
          
    ]);
    
}

    #[Route('/ordonnance/{id}', name: 'ordonnance_delete')]
        public function delete($id, OrdonnanceRepository $repository,ManagerRegistry $doctrine)
        {
            $ordonnance = $repository->find($id);
    
            if (!$ordonnance) {
                throw $this->createNotFoundException('Ordonnance non trouvé');
            }
    
            $em=$doctrine->getManager();
            $em->remove($ordonnance);
            $em->flush();
    
            
            return $this->redirectToRoute('ordonnance_show');
        }
        


}
