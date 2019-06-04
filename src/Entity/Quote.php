<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={
 *      "get"={
 *          "force_eager"=false,
 *          "normalization_context"={"groups"={"read"},"enable_max_depth"=true}
 *       }
 *     },
 *     collectionOperations={
 *       "get"={
 *          "normalization_context"={"groups"={"read"}},
 *       }, 
 *       "post"={
 *          "normalization_context"={"groups"={"write"}},
 *          "swagger_context" = {
 *             "parameters" = {
 *              {
 *                "name" = "quote",
 *                "description" = "Post new Quote example", 
 *                "in" = "body",
 *                "example" = {
 *                      "user": "/api/users/1",
 *                      "exchangeRate": "/api/exchange_rates/1",
 *                      "currencyPurchased": "/api/currencies/74",
 *                      "currencyPurchasedAmount": "100",
 *                      "currencyPaid": "/api/currencies/150",
 *                      "currencyPaidAmount": "0.9920",
 *                      "surchargeAmount": "7.5",
 *                      "surchargePercentage": "7.5",
 *                      "discountAmount": "0",
 *                      "discountPercentage": "0",
 *                      "code": "USDJPY",
 *                      "rate": "108.369854"
 *                 }
 *             }
 *          }
 *       }}
 *     }
 * )
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
     * @Groups({"read", "write"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExchangeRate", inversedBy="quotes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $exchangeRate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="currencyPurchased")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $currencyPurchased;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Groups({"read", "write"})
     */
    private $currencyPurchasedAmount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="currencyPaid")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $currencyPaid;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Groups({"read", "write"})
     */
    private $currencyPaidAmount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4, nullable=true)
     * @Groups({"read", "write"})
     */
    private $surchargeAmount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"read", "write"})
     */
    private $surchargePercentage;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4, nullable=true)
     * @Groups({"read", "write"})
     */
    private $discountAmount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"read", "write"})
     */
    private $discountPercentage;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=6)
     * @Groups({"read", "write"})
     */
    private $code;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     * @Groups({"read", "write"})
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
