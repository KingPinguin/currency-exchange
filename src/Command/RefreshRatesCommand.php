<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Currency;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\ExchangeRate;

class RefreshRatesCommand extends Command
{
    private $params;
    private $manager;

    public function __construct(ParameterBagInterface $params, ObjectManager $manager)
    {
        $this->params = $params;
        $this->manager = $manager;

        parent::__construct();
    }

    protected static $defaultName = 'app:refresh-rates';

    protected function configure()
    {
        $this->setDescription('Create or refresh exchange rates and currencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->refreshCurrencies();
        $this->refreshRates();

        $io->success('Exchange rates and currencies updated!');
    }

    private function refreshCurrencies() {
        $endpoint = 'list';
        // defined in parameters at config/services.yaml
        $access_key = $this->params->get('apilayer_access_key');

        // Initialize CURL:
        $ch = curl_init('http://apilayer.net/api/'.$endpoint.'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);

        // Access the exchange rate values
        foreach($exchangeRates['currencies'] as $currencyCode => $currencyName) {
            // Check if currency already exists in database
            $currencyExists = $this->manager->getRepository('App:Currency')->findOneByCode($currencyCode);
            if(!$currencyExists) {
                $currency = new Currency();
                $currency->setCode($currencyCode);
                $currency->setName($currencyName);

                $this->manager->persist($currency);
            }
        }
        $this->manager->flush();
    }

    private function refreshRates() {
        $endpoint = 'live';
        // defined in parameters at config/services.yaml
        $access_key = $this->params->get('apilayer_access_key');
        $currencies = 'JPY,GBP,EUR';
        $source = 'USD';
        $format = 1;

        // Initialize CURL:
        $ch = curl_init('http://apilayer.net/api/'.$endpoint.'?access_key='.$access_key.'&currencies='.$currencies.'&source='.$source.'&format='.$format.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);

        // Access the exchange rate values
        foreach($exchangeRates['quotes'] as $code => $rate) {
            // Check if exchange rate already exists in database
            $existingExchangeRate = $this->manager->getRepository('App:ExchangeRate')->findOneByCode($code);
            if($existingExchangeRate) {
                $this->setExchangeRates($existingExchangeRate, $code, $rate);
            } else {
                $newExchangeRate = new ExchangeRate();

                $this->setExchangeRates($newExchangeRate, $code, $rate);
            }
        }
        $this->manager->flush();
    }

    private function setExchangeRates($exchangeRate, $code, $rate) {
        $surchargeForUSDJPY = 7.5;
        $surchargeForUSDGBP = 5;
        $surchargeForUSDEUR = 5;
        $discountForUSDEUR = 2;

        $currencySellCode = substr($code, 0, 3);
        $currencyBuyCode = substr($code, -3);

        $currencySell = $this->manager->getRepository('App:Currency')->findOneByCode($currencySellCode);
        $currencyBuy = $this->manager->getRepository('App:Currency')->findOneByCode($currencyBuyCode);

        $exchangeRate->setCurrencySell($currencySell);
        $exchangeRate->setCurrencyBuy($currencyBuy);
        $exchangeRate->setTimestamp(new \DateTime());
        $exchangeRate->setCode($code);
        $exchangeRate->setRate($rate);
        $exchangeRate->setAvailable(true);
        if($code == 'USDJPY') {
            $exchangeRate->setSurchargePercentage($surchargeForUSDJPY);
        } elseif($code == 'USDGBP') {
            $exchangeRate->setSurchargePercentage($surchargeForUSDGBP);
        } elseif($code == 'USDEUR') {
            $exchangeRate->setSurchargePercentage($surchargeForUSDEUR);
            $exchangeRate->setDiscountPercentage($discountForUSDEUR);
        }

        $this->manager->persist($exchangeRate);
    }
}
