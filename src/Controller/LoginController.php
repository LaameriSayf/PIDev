<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\GlobalUser;
use App\Entity\Medecin;
use App\Entity\Patient;
use App\Entity\Pharmacien;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginType;
use App\Repository\GlobalUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormError;
use Doctrine\Persistence\Mapping\ClassMetadata;


class LoginController extends AbstractController{

    #[Route('/login', name: 'login')]
    public function login(GlobalUserRepository $repository, Request $request, ManagerRegistry $doctrine): Response
    {
        $user = new GlobalUser();
        
        $login_form = $this->createForm(LoginType::class, $user);
        $login_form->handleRequest($request);
        
        if ($login_form->isSubmitted()) {
            $email = $user->getEmail();
            $password = $user->getPassword();
            
            $existingAdmin = $doctrine->getRepository(GlobalUser::class)->findOneBy(['email' => $email]);
            
            if ($existingAdmin && password_verify($password, $existingAdmin->getPassword())) {
                //return $this->redirectToRoute("app_afficherAdmin");
                if ($existingAdmin instanceof Admin) {
                    return $this->redirectToRoute("app_afficherAdmin");
                } elseif ($existingAdmin  instanceof Patient) {
                    return $this->redirectToRoute("app_afficherPatient");
                } elseif ($existingAdmin instanceof Medecin) {
                    return $this->redirectToRoute("app_afficherMedecin");
                } elseif ($existingAdmin  instanceof Pharmacien) {
                    return $this->redirectToRoute("app_afficherPharmacien");
                }

            }
            
            $login_form->addError(new FormError('Adresse email ou mot de passe incorrect.')); 
        }
        
        return $this->renderForm("login/login.html.twig", ["login_form" => $login_form]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        return $this->redirectToRoute("app_home");
    }
    }

 
