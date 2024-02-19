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
    public function afficher(ManagerRegistry $doctrine): Response
    {
        $repo=$doctrine->getRepository(Patient::class);
        $Patient=$repo->findAll();
        return $this->render('patient/consulterpatient.html.twig', ['listPatient'=>$Patient]);
    }


}
      

