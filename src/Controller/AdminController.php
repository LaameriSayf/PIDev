<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use App\Form\AdminType;
use Symfony\Component\Form\FormError;





class AdminController extends AbstractController
{    
    
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    
    #[Route('/home', name: 'home_admin')]
    public function home(): Response
    {
        return $this->render('admin/home.html.twig', []);
    }


    #[Route('/addAdmin', name: 'addAdmin')]
    public function addAdmin(Request $req, ManagerRegistry $doctrine): Response
{
    $admin = new Admin();
    $form = $this->createForm(AdminType::class, $admin);

    $form->handleRequest($req);
    if ($form->isSubmitted() && $form->isValid()) {
        $cin = $admin->getCin();
        
        // Vérifier si le cin existe déjà dans la base de données
        $existingAdmin = $doctrine->getRepository(Admin::class)->findOneBy(['cin' => $cin]);
        if ($existingAdmin) {
            // Afficher un message d'erreur
            $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
            // Réafficher le formulaire avec le message d'erreur
            return $this->renderForm("admin/addadmin.html.twig", ["myForm" => $form]);
        }
        
        $em = $doctrine->getManager();
        $em->persist($admin);
        $em->flush();
        
        // Rediriger vers une autre page après l'ajout réussi
        return $this->redirectToRoute('addAdmin');
    }
    
    return $this->renderForm("admin/addadmin.html.twig", ["myForm" => $form]);
}

    
   

    


    
    

#[Route('/afficherAdmin', name: 'app_afficherAdmin')]
 public function affiche(Request $request,ManagerRegistry $doctrine,AdminRepository $AdminRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Admin::class); 
$listAdmin = $searchQuery !== '' ?
        $AdminRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('Admin/consulteradmin.html.twig', [ 'listAdmin' => $listAdmin, 'searchQuery' => $searchQuery, ]);
 }
 
#[Route('/editAdmin/{id}', name: 'app_editAdmin')]
public function edit(AdminRepository $repository, $id, Request $request)
{
    $admin = $repository->find($id);
    $form = $this->createForm(AdminType::class, $admin);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush(); 
        return $this->redirectToRoute("app_afficherAdmin");
    }



return $this->render('admin/editadmin.html.twig', [
    'myForm' => $form->createView(),
]);
}
#[Route('/deleteAdmin/{id}', name: 'app_deleteAdmin')]
    public function delete($id, AdminRepository $repository)
    {
        $admin = $repository->find($id);

        if (!$admin) {
            throw $this->createNotFoundException('Admin non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($admin);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherAdmin');
    }
    #[Route('/ShowAdmin/{id}', name: 'app_showAdmin')]
    public function showAdmin($id, AdminRepository $repository)
    {
        $admin = $repository->find($id);

        if (!$admin) {
            return $this->redirectToRoute('app_afficherAdmin');
        }
        return $this->render('admin/detailsadmin.html.twig',['admin' =>$admin]);
    }

      
}