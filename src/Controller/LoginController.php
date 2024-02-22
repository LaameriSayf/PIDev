<?php

namespace App\Controller;

use App\Entity\GlobalUser;
use App\Form\LoginType;
use App\Repository\GlobalUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(GlobalUserRepository $repository, Request $request): Response
    {
        $user=new GlobalUser();
        $login_form = $this->createForm(LoginType::class, $user);
        $login_form->handleRequest($request);
        if ($login_form->isSubmitted()  
                //&&$login_form->isValid()
                ) {
            $user = $repository->findBy(["email"=>$user->getEmail(),"password"=>$user->getPassword()]);
            return $this->json([
                'user'=>$user
            ]);
          //  return $this->redirectToRoute("app_afficherAdmin");  
    }return $this->renderForm("login/login.html.twig",
    ["login_form" => $login_form,
    ]);
    }
}
