<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use App\Form\PatientType;

class PatientController extends AbstractController
{
    #[Route('/patient', name: 'app_patient')]
    public function index(): Response
    {
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }

#[Route('/addPatient', name: 'addPatient')]
    public function addAdmin(Request $req, ManagerRegistry $doctrine): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($patient);
            $em->flush();
            return $this->redirectToRoute('addPatient');
        }
        
        return $this->renderForm("patient/addpatient.html.twig", ["myForm" => $form]);
    }
    


    #[Route('/afficherPatient', name: 'app_afficherPatient')]
 public function affiche(Request $request,ManagerRegistry $doctrine,PatientRepository $PatientRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Patient::class); 
$listPatient = $searchQuery !== '' ?
        $PatientRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('patient/consulterpatient.html.twig', [ 'listPatient' => $listPatient, 'searchQuery' => $searchQuery, ]);
 }
 


    
    #[Route('/editPatient/{id}', name: 'app_editPatient')]
public function edit(PatientRepository $repository, $id, Request $request)
{
    $patient = $repository->find($id);
    $form = $this->createForm(PatientType::class, $patient);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
        return $this->redirectToRoute("app_afficherPatient");
    }



return $this->render('patient/editpatient.html.twig', [
    'myForm' => $form->createView(),
]);
}
#[Route('/deletePatient/{id}', name: 'app_deletePatient')]
    public function delete($id, PatientRepository $repository)
    {
        $patient = $repository->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('patient non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($patient);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherPatient');
    }
    #[Route('/ShowPatient/{id}', name:'app_showPatient')]
    public function showPatient($id, PatientRepository $repository)
    {
        $patient = $repository->find($id);

        if (!$patient) {
            return $this->redirectToRoute('app_afficherPatient');
        }
        return $this->render('patient/detailspatient.html.twig',['patient' =>$patient]);
    }
      
}



      

