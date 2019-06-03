<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *       }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 */
class Currency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"read"})
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExchangeRate", mappedBy="currencySell", orphanRemoval=true)
     */
    private $sellExchangeRates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExchangeRate", mappedBy="currencyBuy", orphanRemoval=true)
     */
    private $buyExchangeRates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quote", mappedBy="currencyPurchased")
     */
    private $currencyPurchased;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quote", mappedBy="currencyPaid")
     */
    private $currencyPaid;

    public function __construct()
    {
        $this->sellExchangeRates = new ArrayCollection();
        $this->buyExchangeRates = new ArrayCollection();
        $this->currencyPurchased = new ArrayCollection();
        $this->currencyPaid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection|ExchangeRate[]
     */
    public function getSellExchangeRates(): Collection
    {
        return $this->sellExchangeRates;
    }

    public function addSellExchangeRate(ExchangeRate $sellExchangeRate): self
    {
        if (!$this->sellExchangeRates->contains($sellExchangeRate)) {
            $this->sellExchangeRates[] = $sellExchangeRate;
            $sellExchangeRate->setCurrencySell($this);
        }

        return $this;
    }

    public function removeSellExchangeRate(ExchangeRate $sellExchangeRate): self
    {
        if ($this->sellExchangeRates->contains($sellExchangeRate)) {
            $this->sellExchangeRates->removeElement($sellExchangeRate);
            // set the owning side to null (unless already changed)
            if ($sellExchangeRate->getCurrencySell() === $this) {
                $sellExchangeRate->setCurrencySell(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExchangeRate[]
     */
    public function getBuyExchangeRates(): Collection
    {
        return $this->buyExchangeRates;
    }

    public function addBuyExchangeRate(ExchangeRate $buyExchangeRate): self
    {
        if (!$this->buyExchangeRates->contains($buyExchangeRate)) {
            $this->buyExchangeRates[] = $buyExchangeRate;
            $buyExchangeRate->setCurrencyBuy($this);
        }

        return $this;
    }

    public function removeBuyExchangeRate(ExchangeRate $buyExchangeRate): self
    {
        if ($this->buyExchangeRates->contains($buyExchangeRate)) {
            $this->buyExchangeRates->removeElement($buyExchangeRate);
            // set the owning side to null (unless already changed)
            if ($buyExchangeRate->getCurrencyBuy() === $this) {
                $buyExchangeRate->setCurrencyBuy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quote[]
     */
    public function getCurrencyPurchased(): Collection
    {
        return $this->currencyPurchased;
    }

    public function addCurrencyPurchased(Quote $currencyPurchased): self
    {
        if (!$this->currencyPurchased->contains($currencyPurchased)) {
            $this->currencyPurchased[] = $currencyPurchased;
            $currencyPurchased->setCurrencyPurchased($this);
        }

        return $this;
    }

    public function removeCurrencyPurchased(Quote $currencyPurchased): self
    {
        if ($this->currencyPurchased->contains($currencyPurchased)) {
            $this->currencyPurchased->removeElement($currencyPurchased);
            // set the owning side to null (unless already changed)
            if ($currencyPurchased->getCurrencyPurchased() === $this) {
                $currencyPurchased->setCurrencyPurchased(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quote[]
     */
    public function getCurrencyPaid(): Collection
    {
        return $this->currencyPaid;
    }

    public function addCurrencyPaid(Quote $currencyPaid): self
    {
        if (!$this->currencyPaid->contains($currencyPaid)) {
            $this->currencyPaid[] = $currencyPaid;
            $currencyPaid->setCurrencyPaid($this);
        }

        return $this;
    }

    public function removeCurrencyPaid(Quote $currencyPaid): self
    {
        if ($this->currencyPaid->contains($currencyPaid)) {
            $this->currencyPaid->removeElement($currencyPaid);
            // set the owning side to null (unless already changed)
            if ($currencyPaid->getCurrencyPaid() === $this) {
                $currencyPaid->setCurrencyPaid(null);
            }
        }

        return $this;
    }
}
