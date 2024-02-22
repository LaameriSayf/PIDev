<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        // Méthode pour afficher la page d'index de la catégorie
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }
    
    #[Route('/addCategorie', name: 'addCategorie')]
    public function addCategorie(Request $req, ManagerRegistry $doctrine): Response
    { 
        // Méthode pour ajouter une nouvelle catégorie
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($req);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtenir le gestionnaire d'entités
            $em = $doctrine->getManager();
    
            // Persister la nouvelle entité Catégorie
            $em->persist($categorie);
    
            // Enregistrer les modifications dans la base de données
            $em->flush();
    
            // Rediriger vers la route pour afficher la liste des catégories
            return $this->redirectToRoute('app_afficherCategorie');
        }
    
        // Rendre le formulaire pour ajouter une catégorie
        return $this->renderForm("categorie/AjouterCategorie/add.html.twig", ["myForm" => $form]);
    }
    
    #[Route('/afficherCategorie', name: 'app_afficherCategorie')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        // Méthode pour afficher la liste des catégories
        $repo = $doctrine->getRepository(Categorie::class);
        $categorie = $repo->findAll();
    
        // Rendre le modèle pour afficher la liste des catégories
        return $this->render('categorie/ConsulterCategorie/list.html.twig', ['listCategorie' => $categorie]);
    }
    
    #[Route('/editCategorie/{id}', name: 'app_editCategorie')]
    public function edit(CategorieRepository $repository, $id, Request $request, ManagerRegistry $doctrine)
    {
        // Méthode pour éditer une catégorie existante
        $categorie = $repository->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtenir le gestionnaire d'entités
            $em = $doctrine->getManager();
    
            // Enregistrer les modifications dans la base de données
            $em->flush();
    
            // Rediriger vers la route pour afficher la liste des catégories
            return $this->redirectToRoute("app_afficherCategorie");
        }
    
        // Rendre le formulaire pour éditer une catégorie
        return $this->render('categorie/ConsulterCategorie/edit.html.twig', [
            'myForm' => $form->createView(),
        ]);
    }
    
    #[Route('/deleteCategorie/{id}', name: 'app_deleteCategorie')]
    public function delete($id, CategorieRepository $repository, ManagerRegistry $doctrine)
    {
        // Méthode pour supprimer une catégorie
        $categorie = $repository->find($id);
    
        // Vérifier si la catégorie existe
        if (!$categorie) {
            throw $this->createNotFoundException('Categorie non trouvée');
        }
        $em = $doctrine->getManager();
    
        $medicaments = $categorie->getMedicaments();

        foreach ($medicaments as $medicament) {
            $em->remove($medicament);
        }
    
        // Obtenir le gestionnaire d'entités
      
        // Supprimer la catégorie
        $em->remove($categorie);
    
        // Enregistrer les modifications dans la base de données
        $em->flush();
    
        // Rediriger vers la route pour afficher la liste des catégories
        return $this->redirectToRoute('app_afficherCategorie');
    }
    
      
}
