<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Entity\Categorie;
use App\Entity\PropertySearch;
use App\Form\MedicamentType;
use App\Form\PropertySearchType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\MedicamentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MedicamentController extends AbstractController
{
  
  
    #[Route('/medicament', name: 'app_medicament1')]
    public function index(ManagerRegistry $doctrine,Request $request): Response
    {
       
        {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);
       //initialement le tableau des articles est vide, 
       //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
        $medicament= [];
        $medicament= $doctrine->getRepository(Medicament::class)->findAll();
       if($form->isSubmitted() && $form->isValid()) {
       //on récupère le nom d'article tapé dans le formulaire
        $nom = $propertySearch->getNom();   
        if ($nom!="") 
          //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
          $medicament=  $doctrine->getRepository(Medicament::class)->findBy(['nom_med' => $nom] );
       
       }
       
       $repo1 = $doctrine->getRepository(Categorie::class);
       $categorie = $repo1->findAll();
        return $this->render('medicament/FontOffice.html.twig', 
             ['form' =>$form->createView(),
             'list' => $medicament,
             'listCategorie' => $categorie,],
        );
    }}


    #[Route('/addMedicament', name: 'app_addMedicament')]
    public function addMedicament(Request $req, ManagerRegistry $doctrine): Response
    { 
        // Créer une nouvelle instance de l'entité Medicament
        $medicament = new Medicament();
        
        // Créer un formulaire pour l'entité Medicament en utilisant le type de formulaire MedicamentType
        $form = $this->createForm(MedicamentType::class, $medicament);
     
        // Gérer la soumission du formulaire
        $form->handleRequest($req);
        
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtenir le gestionnaire d'entités
            $em = $doctrine->getManager();
            
            // Persister la nouvelle entité Medicament
            $em->persist($medicament);
            
            // Enregistrer les modifications dans la base de données
            $em->flush();
            
            // Rediriger vers la route pour afficher la liste des Medicaments
            return $this->redirectToRoute('app_afficherMedicament');
        }
        
        // Rendre le modèle de formulaire s'il n'est pas valide ou non soumis
        return $this->renderForm("medicament/AjouterMedicament/add1.html.twig", ["myForm" => $form]);
    }
    
    #[Route('/afficherMedicament', name: 'app_afficherMedicament')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        // Obtenir le dépôt pour l'entité Medicament
        $repo = $doctrine->getRepository(Medicament::class);
        
        // Récupérer toutes les entités Medicament de la base de données
        $medicament = $repo->findAll();
        
        // Rendre le modèle pour afficher la liste des Medicaments
        return $this->render('medicament/ConsulterMedicament/list2.html.twig', ['list' => $medicament]);
    }
  
    
    #[Route('/editMedicament/{id}', name: 'app_editMedicament')]
    public function edit(MedicamentRepository $repository, $id, Request $request, ManagerRegistry $doctrine)
    {
        // Trouver l'entité Medicament par son ID
        $medicament = $repository->find($id);
        
        // Créer un formulaire pour éditer l'entité Medicament
        $form = $this->createForm(MedicamentType::class, $medicament);
        
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtenir le gestionnaire d'entités
            $em = $doctrine->getManager();
            
            // Enregistrer les modifications dans la base de données
            $em->flush();
            
            // Rediriger vers la route pour afficher la liste des Medicaments
            return $this->redirectToRoute("app_afficherMedicament");
        }
    
        // Rendre le modèle de formulaire pour éditer l'entité Medicament
        return $this->render('medicament/ConsulterMedicament/edit.html.twig', [
            'myForm' => $form->createView(),
        ]);
    }
    
    #[Route('/deleteMedicament/{id}', name: 'app_deleteMedicament')]
    public function delete($id, MedicamentRepository $repository, ManagerRegistry $doctrine)
    {
        // Trouver l'entité Medicament par son ID
        $medicament = $repository->find($id);
        
        // Obtenir le gestionnaire d'entités
        $em = $doctrine->getManager();
        
        // Supprimer l'entité Medicament
        $em->remove($medicament);
        
        // Enregistrer les modifications dans la base de données
        $em->flush();
        
        // Rediriger vers la route pour afficher la liste des Medicaments
        return $this->redirectToRoute('app_afficherMedicament');
    }}
