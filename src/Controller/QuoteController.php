<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /**
     * @Route("/quote", name="app_quote")
     */
    public function index()
    {
//        $client = new Client(['headers' => ['Accept' => 'application/ld+json']]);
//        $data = $client->get('http://localhost:8001/api/currencies');
//        $network = json_decode($data->getBody(), true);
//        dump($network['hydra:member']);

        return $this->render('quote/index.html.twig', [
            'controller_name'   => 'QuoteController',
//            'data'              =>  json_decode($data)
        ]);
    }
}
