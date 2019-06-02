<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRateRepository")
 */
class ExchangeRate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="sellExchangeRates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currencySell;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="buyExchangeRates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currencyBuy;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $rate;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $surchargePercentage;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discountPercentage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $available;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quote", mappedBy="exchangeRate")
     */
    private $quotes;

    public function __construct()
    {
        $this->quotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCurrencySell(): ?Currency
    {
        return $this->currencySell;
    }

    public function setCurrencySell(?Currency $currencySell): self
    {
        $this->currencySell = $currencySell;

        return $this;
    }

    public function getCurrencyBuy(): ?Currency
    {
        return $this->currencyBuy;
    }

    public function setCurrencyBuy(?Currency $currencyBuy): self
    {
        $this->currencyBuy = $currencyBuy;

        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getSurchargePercentage()
    {
        return $this->surchargePercentage;
    }

    public function setSurchargePercentage($surchargePercentage): self
    {
        $this->surchargePercentage = $surchargePercentage;

        return $this;
    }

    public function getDiscountPercentage()
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage($discountPercentage): self
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(?bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return Collection|Quote[]
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): self
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes[] = $quote;
            $quote->setExchangeRate($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): self
    {
        if ($this->quotes->contains($quote)) {
            $this->quotes->removeElement($quote);
            // set the owning side to null (unless already changed)
            if ($quote->getExchangeRate() === $this) {
                $quote->setExchangeRate(null);
            }
        }

        return $this;
    }
}
