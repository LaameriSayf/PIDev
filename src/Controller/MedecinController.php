<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Repository\MedecinRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use App\Form\MedecinType;


class MedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_medecin')]
    public function index(): Response
    {
        return $this->render('medecin/index.html.twig', [
            'controller_name' => 'MedecinController',
        ]);
    }
    #[Route('/addMedecin', name: 'addMedecin')]
    public function addMedecin(Request $req, ManagerRegistry $doctrine): Response
    {
        $medecin = new medecin();
        $form = $this->createForm(MedecinType::class, $medecin);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($medecin);
            $em->flush();
            return $this->redirectToRoute('addMedecin');
        }
        
        return $this->renderForm("medecin/addmedecin.html.twig", ["myForm" => $form]);
    }
  
    #[Route('/afficherMedecin', name: 'app_afficherMedecin')]
 public function affiche(Request $request,ManagerRegistry $doctrine,MedecinRepository $MedecinRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Medecin::class); 
$listMedecin = $searchQuery !== '' ?
        $MedecinRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('medecin/consultermedecin.html.twig', [ 'listMedecin' => $listMedecin, 'searchQuery' => $searchQuery, ]);
 }
 

    
    #[Route('/editMedecin/{id}', name: 'app_editMedecin')]
public function edit(MedecinRepository $repository, $id, Request $request)
{
    $medecin = $repository->find($id);
    $form = $this->createForm(MedecinType::class, $medecin);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
        return $this->redirectToRoute("app_afficherMedecin");
    }



return $this->render('medecin/editmedecin.html.twig', [
    'myForm' => $form->createView(),
]);
}
#[Route('/deleteMedecin/{id}', name: 'app_deleteMedecin')]
    public function delete($id, MedecinRepository $repository)
    {
        $medecin = $repository->find($id);

        if (!$medecin) {
            throw $this->createNotFoundException('Medecin non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($medecin);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherMedecin');
    }
    #[Route('/ShowMedecin/{id}', name: 'app_showMedecin')]
    public function showMedecin($id, MedecinRepository $repository)
    {
        $medecin = $repository->find($id);

        if (!$medecin) {
            return $this->redirectToRoute('app_afficherMedecin');
        }
        return $this->render('medecin/detailsmedecin.html.twig',['medecin' =>$medecin]);
    }
}