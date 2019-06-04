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

            return new JsonResponse($this->calculateResults($form));
        }

        return $this->render('quote/index.html.twig', [
            'form'  =>  $form->createView(),
        ]);
    }

    private function calculateResults($form) {
        $purchasedAmount = $form->get('amount')->getData();
        $exchangeRateId = $form->get('currency')->getData();
        $entityManager = $this->getDoctrine()->getManager();
        $exchangeRate = $entityManager->getRepository('App:ExchangeRate')->find($exchangeRateId);
        return $result = [
            'user' => '/api/users/'.$this->getUser()->getId(),
            'exchangeRate'=> '/api/exchange_rates/'.$exchangeRateId.'',
            'currencyPurchased' => '/api/currencies/'.$exchangeRate->getCurrencyBuy()->getId().'',
            'currencyPurchasedAmount' => ''.$purchasedAmount.'',
            'currencyPaid' => '/api/currencies/'.$exchangeRate->getCurrencySell()->getId().'',
            'currencyPaidAmount' => ''.$this->paidAmount($purchasedAmount, $this->discountAmount($purchasedAmount, $exchangeRate->getDiscountPercentage(), $this->surchargeAmount($purchasedAmount, $exchangeRate->getSurchargePercentage())), $this->surchargeAmount($purchasedAmount, $exchangeRate->getSurchargePercentage()), $exchangeRate->getRate()).'',
            'surchargeAmount' => ''.$this->surchargeAmount($purchasedAmount, $exchangeRate->getSurchargePercentage()).'',
            'surchargePercentage' => ''.$exchangeRate->getSurchargePercentage().'',
            'discountAmount' => ''.$this->discountAmount($purchasedAmount, $exchangeRate->getDiscountPercentage(), $this->surchargeAmount($purchasedAmount, $exchangeRate->getSurchargePercentage())).'',
            'discountPercentage' => ''.$exchangeRate->getDiscountPercentage().'',
            'code' => ''.$exchangeRate->getCode().'',
            'rate' => ''.$exchangeRate->getRate().''
        ];
    }

    private function surchargeAmount($purchasedAmount, $surchargePercentage) {
        return $surchargeAmount = ($surchargePercentage / 100) * $purchasedAmount;
    }

    private function discountAmount($purchasedAmount, $discountPercentage, $surchargeAmount) {
        if(!IS_NULL($discountPercentage)) {

            return $discountAmount = ($discountPercentage / 100) * ($purchasedAmount + $surchargeAmount);
        } else {

            return 0;
        }
    }

    private function paidAmount($purchasedAmount, $discountAmount, $surchargeAmount, $rate) {

        return $paidAmount = ($purchasedAmount - $discountAmount + $surchargeAmount) / $rate;
    }
}
