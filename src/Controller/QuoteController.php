<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuoteController extends AbstractController
{
    /**
     * @Route("/quotes", name="app_quote")
     */
    public function getQuote(Request $request)
    {
        $client = new Client(['headers' => ['Accept' => 'application/ld+json']]);
        $data = $client->get('http://localhost:8001/api/exchange_rates');
        $network = json_decode($data->getBody(), true);
        $currencies = [];
        foreach($network['hydra:member'] as $exchangeRate) {
            $currencies[$exchangeRate['currencyBuy']['name']] = $exchangeRate['id'];
        }

        $form = $this->createFormBuilder()
            ->add('amount', NumberType::class, array('attr' => array('class' => 'form-control')))
            ->add('currency', ChoiceType::class, array(
                'placeholder' => 'Choose...', 
                'choices' => $currencies, 
                'attr'    => array('class' => 'form-control')
            ))->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            return new JsonResponse($form->get('amount')->getData());
        }

        return $this->render('quote/index.html.twig', [
            'form'  =>  $form->createView(),
        ]);
    }
}
