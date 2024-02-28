<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Form\EditRendezType;
use App\Form\RendezvousType;
use App\Repository\RendezvousRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;







class RendezvousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendezvous')]
    public function index(): Response
    {
        return $this->render('rendezvous/index.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);
    }
    
#[Route('/addRendezvous', name: 'app_addRendezvous')]
public function addRendezvous(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
{   $rendezvous = new Rendezvous();
    $form = $this->createForm(RendezvousType::class, $rendezvous);
    $form->handleRequest($request);

    if ($form->isSubmitted() ) {
        $file = $form->get('file')->getData();
        if($file){ 
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            try{ $file->move('C:\Users\halim\OneDrive\Bureau\PIDev\uploads',
                $newFilename
            );} catch(FileException $e){
            }
           
    
            $rendezvous->setFile($newFilename);        }


        $em = $doctrine->getManager();
        $existingRendezvous = $em->getRepository(Rendezvous::class)->findOneBy([
            'daterendezvous' => $rendezvous->getDaterendezvous(),
            'heurerendezvous' => $rendezvous->getHeurerendezvous(),
        ]);

        if ($existingRendezvous !== null) {
            $this->addFlash('error', 'Rendez-vous déjà existant.');
            return $this->redirectToRoute('app_addRendezvous');
   
}

        $em->persist($rendezvous);
        $em->flush();
        $this->addFlash('success', 'Rendez-vous ajouté avec succès.');

    }

    return $this->render('rendezvous/ajouterRdv/ajouterRdv.html.twig',  ['form' => $form->createView(),]);
}


    
   

    #[Route('/editRendezvous/{id}', name: 'app_editRendezvous')]
    public function editRendezvous(RendezvousRepository $repository, $id, Request $request,ManagerRegistry $manager)
    {
        $rendezvous = $repository->find($id);
        $form = $this->createForm(EditRendezType::class, $rendezvous);
        $form->handleRequest($request);
        $em = $manager->getManager();

        if ($form->isSubmitted()&& $form->isValid()) {
           
                $em->persist($rendezvous);
                $em->flush();
                $this->addFlash('success', 'Rendez-vous mis a jour avec succès.');
            }
            
        
    
        return $this->render('rendezvous/consulterRdv/modifierRdv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deleteRendezvous/{id}', name: 'app_deleteRendezvous')]
    public function deleteRendezvous($id, RendezvousRepository $repository,ManagerRegistry $manager)
    {

        $rendezVous = $repository->find($id);
        $em = $manager->getManager();
        $em->remove($rendezVous);
        $em->flush();
        $this->addFlash('success', 'Rendez vous annuler!');

       return $this->redirectToRoute('app_afficherRendezVous');




    }
    #[Route('/afficherRendezVous', name: 'app_afficherRendezVous')]

    public function afficherRendezVous(ManagerRegistry $doctrine):Response
    {

        $repository=$doctrine->getRepository(Rendezvous::class);
        $rendezvous=$repository->findAll();
        return $this->render('rendezvous/consulterRdv/afficherRdv.html.twig', ['list'=>$rendezvous]);
    }

    #[Route('/afficherRendezVousMedecin', name: 'app_afficherRendezVousMedecin')]

    public function afficherRendezVousMedcin(ManagerRegistry $doctrine):Response
    {
        $repository=$doctrine->getRepository(Rendezvous::class);
        $rendezvous=$repository->findAll();
        return $this->render('rendezvous/consulterRdv/afficherRdvMedecin.html.twig', ['list'=>$rendezvous]);
    }

    #[Route('/searchRendezvousByDate', name: 'app_searchRendezvousByDate')]
public function searchRendezvousByDate(Request $request, RendezvousRepository $repository):Response
{
    $date = $request->query->get('daterendezvous');

    $rendezvous = $repository->findByDate($date);

    $responseArray = [];
    foreach ($rendezvous as $rdv) {
        $responseArray[] = [
            'id' => $rdv->getId(),
            'daterendezvous' => $rdv->getDaterendezvous()->format('Y-m-d'),
            'heurerendezvous' => $rdv->getHeurerendezvous()->format('H:i:s'),
            'description' => $rdv->getDescription(),
            'file' => $rdv->getFile(),
        ];
    }

    return $this->render('rendezvous/consulterRdv/afficherRdvMedecin.html.twig', ['list' => $responseArray]);}




    
}
