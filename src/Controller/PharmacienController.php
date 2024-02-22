<?php

namespace App\Controller;

use App\Entity\Pharmacien;
use App\Form\PharmacienType;
use App\Repository\PharmacienRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use App\Form\PharmacienTypeType;
use Symfony\Component\Form\FormError;




class PharmacienController extends AbstractController
{
    #[Route('/pharmacien', name: 'app_pharmacien')]
    public function index(): Response
    {
        return $this->render('pharmacien/index.html.twig', [
            'controller_name' => 'pharmacienController',
        ]);
    }

    #[Route('/addPharmacien', name: 'addPharmacien')]
    public function addPharmacien(Request $req, ManagerRegistry $doctrine): Response
    {
        $pharmacien = new Pharmacien();
        $form = $this->createForm(PharmacienType::class, $pharmacien);
    
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $cin = $pharmacien->getCin();
            
            // Vérifier si le cin existe déjà dans la base de données
            $existingPharmacien = $doctrine->getRepository(Pharmacien::class)->findOneBy(['cin' => $cin]);
            if ($existingPharmacien) {
                // Afficher un message d'erreur
                $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
                // Réafficher le formulaire avec le message d'erreur
                return $this->renderForm("admin/addadmin.html.twig", ["myForm" => $form]);
            }
            
            $em = $doctrine->getManager();
            $em->persist($pharmacien);
            $em->flush();
            
            // Rediriger vers une autre page après l'ajout réussi
            return $this->redirectToRoute('addPharmacien');
        }
        
        return $this->renderForm("pharmacien/addpharmacien.html.twig", ["myForm" => $form]);
    }
    

#[Route('/afficherPharmacien', name: 'app_afficherPharmacien')]
 public function affiche(Request $request,ManagerRegistry $doctrine,PharmacienRepository $PharmacienRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Pharmacien::class); 
$listPharmacien = $searchQuery !== '' ?
        $PharmacienRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('Pharmacien/consulterpharmacien.html.twig', [ 'listPharmacien' => $listPharmacien, 'searchQuery' => $searchQuery, ]);
 }
 
#[Route('/editPharmacien/{id}', name: 'app_editPharmacien')]
public function edit(PharmacienRepository $repository, $id, Request $request)
{
    $pharmacien = $repository->find($id);
    $form = $this->createForm(PharmacienType::class, $pharmacien);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush(); 
        return $this->redirectToRoute("app_afficherPharmacien");
    }



return $this->render('pharmacien/editpharmacien.html.twig', [
    'myForm' => $form->createView(),
]);
}
#[Route('/deletePharmacien/{id}', name: 'app_deletePharmacien')]
    public function delete($id, PharmacienRepository $repository)
    {
        $pharmacien = $repository->find($id);

        if (!$pharmacien) {
            throw $this->createNotFoundException('Pharmacien non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($pharmacien);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherPharmacien');
    }
    #[Route('/ShowPharmacien/{id}', name: 'app_showPharmacien')]
    public function showPharmacien($id, PharmacienRepository $repository)
    {
        $pharmacien = $repository->find($id);

        if (!$pharmacien) {
            return $this->redirectToRoute('app_afficherPharmacien');
        }
        return $this->render('pharmacien/detailspharmacien.html.twig',['pharmacien' =>$pharmacien]);
    }

      
}
