<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\QuoteRepository")
 */
class Quote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="quotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExchangeRate", inversedBy="quotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exchangeRate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="currencyPurchased")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currencyPurchased;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $currencyPurchasedAmount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="currencyPaid")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currencyPaid;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $currencyPaidAmount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4, nullable=true)
     */
    private $surchargeAmount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $surchargePercentage;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4, nullable=true)
     */
    private $discountAmount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discountPercentage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="guid", unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $code;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $rate;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExchangeRate(): ?ExchangeRate
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(?ExchangeRate $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getCurrencyPurchased(): ?Currency
    {
        return $this->currencyPurchased;
    }

    public function setCurrencyPurchased(?Currency $currencyPurchased): self
    {
        $this->currencyPurchased = $currencyPurchased;

        return $this;
    }

    public function getCurrencyPurchasedAmount()
    {
        return $this->currencyPurchasedAmount;
    }

    public function setCurrencyPurchasedAmount($currencyPurchasedAmount): self
    {
        $this->currencyPurchasedAmount = $currencyPurchasedAmount;

        return $this;
    }

    public function getCurrencyPaid(): ?Currency
    {
        return $this->currencyPaid;
    }

    public function setCurrencyPaid(?Currency $currencyPaid): self
    {
        $this->currencyPaid = $currencyPaid;

        return $this;
    }

    public function getCurrencyPaidAmount()
    {
        return $this->currencyPaidAmount;
    }

    public function setCurrencyPaidAmount($currencyPaidAmount): self
    {
        $this->currencyPaidAmount = $currencyPaidAmount;

        return $this;
    }

    public function getSurchargeAmount()
    {
        return $this->surchargeAmount;
    }

    public function setSurchargeAmount($surchargeAmount): self
    {
        $this->surchargeAmount = $surchargeAmount;

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

    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount($discountAmount): self
    {
        $this->discountAmount = $discountAmount;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

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

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
