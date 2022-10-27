<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BonjourController
{
    #[Route('/bonjour')]
    public function bonjour()
    {
        return new Response('Bonjour tout le monde !');
    }

}