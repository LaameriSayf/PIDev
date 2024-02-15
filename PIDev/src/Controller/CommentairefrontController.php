<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Blog;
use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class CommentairefrontController extends AbstractController
{

    #[Route('/commentairefront', name: 'app_commentairefront')]
    public function affichee(Request $request, ManagerRegistry $doctrine): Response
    {
        $commentRepository = $doctrine->getRepository(Commentaire::class);
        $comment = $commentRepository->findAll();
        return $this->render('commentairefront/index.html.twig', ['commentitems' => $comment,]);
    }

 /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Request $request,$id,ManagerRegistry $doctrine)
    {
       
        $blogPost = $this->getDoctrine()->getRepository(Blog::class)->find($id);
        
        if (!$blogPost) {
            throw $this->createNotFoundException('L\'article de blog n\'existe pas');
        }
        
        $blogUrl = $this->generateUrl('blog_show', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    
        
        return $this->render('blogfront/showauthordetaille.html.twig', [
            'blogPost' => $blogPost,
            'blogUrl' => $blogUrl,
        ]);
    }
    #[Route('/jaime/{id}', name: 'app_commentaire_jaime', )]
    public function jaime(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {        
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);
        $admin = $entityManager->getRepository(Admin::class)->find(1); // Utilisateur avec ID statique 1

        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }

        // Vérifier si l'administrateur a déjà aimé ce commentaire
        if ($commentaire->getIdadmin() === $admin && $commentaire->isJaime() === true) {
            // Gérer le cas où l'administrateur a déjà aimé ce commentaire
            // Rediriger ou afficher un message approprié
        }

        // Marquer le commentaire comme aimé par l'administrateur
        $commentaire->setJaime(true);
        $commentaire->setNejaimepas(false);
        $commentaire->setIdadmin($admin);
        $entityManager->flush();

        // Rediriger vers la page d'affichage des articles après le traitement
        return $this->redirectToRoute('blogdetails', ['id' => $commentaire->getIdblog()->getId()]);
    }

    #[Route('/nejaimepas/{id}', name: 'app_commentaire_nejaimepas', )]
    public function neJaimePas(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {        
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);
        $admin = $entityManager->getRepository(Admin::class)->find(1); // Utilisateur avec ID statique 1

        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }

        // Vérifier si l'administrateur a déjà indiqué qu'il n'aime pas ce commentaire
        if ($commentaire->getIdadmin() === $admin && $commentaire->isNejaimepas() === true) {
            // Gérer le cas où l'administrateur a déjà indiqué qu'il n'aime pas ce commentaire
            // Rediriger ou afficher un message approprié
        }

        // Marquer le commentaire comme n'étant pas aimé par l'administrateur
        $commentaire->setNejaimepas(true);
        $commentaire->setJaime(false);
        $commentaire->setIdadmin($admin);
        $entityManager->flush();

        // Rediriger vers la page d'affichage des articles après le traitement
        return $this->redirectToRoute('blogdetails', ['id' => $commentaire->getIdblog()->getId()]);
    }
}
