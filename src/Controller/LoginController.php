<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request): Response
    {
        $form= $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email= $data['Adresse_email'];
            $password= $data['Mot_de_passe'];
            return $this->render('login/success.html.twig', [
                'email' => $email, 'password' => $password
            ]);
        } else {
            return $this->renderForm('login/index.html.twig', [
                'controller_name' => 'LoginController',
                'formulaire' => $form
            ]);
        }

    }
}
