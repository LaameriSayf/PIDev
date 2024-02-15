<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogbackController extends AbstractController
{
    #[Route('/blogback', name: 'app_blogback')]
    public function index(): Response
    {
        return $this->render('blogback/index.html.twig', [
            'controller_name' => 'BlogbackController',
        ]);
    }
}
