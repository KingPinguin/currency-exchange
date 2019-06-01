<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /**
     * @Route("/quote", name="quote")
     */
    public function index()
    {
        return $this->render('quote/index.html.twig', [
            'controller_name' => 'QuoteController',
        ]);
    }
}
