<?php

namespace App\Controller;

use App\Entity\GlobalUser;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\PatientType;
use App\Form\RegistrationType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class PatientController extends AbstractController
{
    #[Route('/patient', name: 'app_patient')]
    public function index(): Response
    {
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }

    #[Route('/inscri', name: 'inscri')]
    public function inscri(Request $req, ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $patient = new patient();
        $form = $this->createForm(RegistrationType::class, $patient);
    
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $cin = $patient->getCin();
            
            // Vérifier si le cin existe déjà dans la base de données
            $existingPatient = $doctrine->getRepository(Patient::class)->findOneBy(['cin' => $cin]);
            if ($existingPatient) {
                // Afficher un message d'erreur
                $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
                // Réafficher le formulaire avec le message d'erreur
                return $this->renderForm("inscription/registration.html.twig", ["form" => $form]);
            }
            $patient->setInterlock(0);
           
            // Hash the password securely before storing it
            //$hashedPassword = $passwordEncoder->encodePassword($patient, $form->get('Password')->getData());
            //$patient->setPassword($hashedPassword);

            $em = $doctrine->getManager();
            $em->persist($patient);
            $em->flush();
            
            // Rediriger vers une autre page après l'ajout réussi
            return $this->redirectToRoute('login');
        }
        
        return $this->renderForm("inscription/registration.html.twig", ["form" => $form]);
    }




    
#[Route('/addPatient', name: 'addPatient')]
public function addPatient(Request $req, ManagerRegistry $doctrine): Response
{
    $patient = new Patient();
    $form = $this->createForm(PatientType::class, $patient);

    $form->handleRequest($req);
    if ($form->isSubmitted() && $form->isValid()) {
       
        $cin = $patient->getCin();
        
        // Vérifier si le cin existe déjà dans la base de données
        $existingPatient = $doctrine->getRepository(Patient::class)->findOneBy(['cin' => $cin]);
        if ($existingPatient) {
            // Afficher un message d'erreur
            $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
            // Réafficher le formulaire avec le message d'erreur
            return $this->renderForm("patient/addpatient.html.twig", ["myForm" => $form]);
        }
      
        $em = $doctrine->getManager();
        $em->persist($patient);
        $em->flush();
        
        // Rediriger vers une autre page après l'ajout réussi
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



      

