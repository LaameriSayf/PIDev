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
    $form=$this->createForm(OrdonnanceType::class,$ordonnance);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()){
        
        $em=$doctrine->getManager();
        $em->persist($ordonnance);
        $em->flush(); 
        return $this->redirectToRoute('show_dossier');
    }
    
    return $this->renderForm("ordonnance/add.html.twig", ["form"=>$form]);
    }
    
    #[Route('/showOrdonnance', name: 'ordonnance_show')]
    public function show(ManagerRegistry $doctrine): Response
    {
  
    $repo=$doctrine->getRepository(Ordonnance::class);
    $ordonnance=$repo->findAll();

    return $this->render('ordonnance/show.html.twig', ['listOrdonnances'=>$ordonnance]);
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
