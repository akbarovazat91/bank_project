<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreditRepository")
 */
class Credit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="credit")
     */
    private $payments;

    /**
     * Credit constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->date = new \DateTime("now");
    }


    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="credits")
     *
     */
    private $client;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $sum;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $amount_with_costing;


    /**
     * @ORM\Column(type="decimal")
     */
    private $interest_rate;

    /**
     * @ORM\Column(type="integer")
     */
    private $credit_term;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function setSum($sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param mixed $interest_rate
     * @return Credit
     */
    public function setInterestRate($interest_rate)
    {
        $this->interest_rate = $interest_rate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInterestRate()
    {
        return $this->interest_rate;
    }

    /**
     * @param mixed $credit_term
     * @return Credit
     */
    public function setCreditTerm($credit_term)
    {
        $this->credit_term = $credit_term;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditTerm()
    {
        return $this->credit_term;
    }

    /**
     * @param mixed $amount_with_costing
     * @return Credit
     */
    public function setAmountWithCosting($amount_with_costing)
    {
        $this->amount_with_costing = $amount_with_costing;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmountWithCosting()
    {
        return $this->amount_with_costing;
    }

    /**
     * @return ArrayCollection
     */
    public function getPayments(): ArrayCollection
    {
        return $this->payments;
    }

    /**
     * @return mixed
     */
    public function getRemainder()
    {
        $this->amount_with_costing;
        $total_sum = $this->getPaidAmount();
        return $this->amount_with_costing - $total_sum;
    }

    /**
     * @return mixed
     */
    public function getPaidAmount()
    {
        $payments = $this->payments;
        $total_sum = 0;
        foreach ($payments as $payment) {
            $total_sum += $payment->getSum();
        }
        return $total_sum;
    }

    public function getAnnuityRatio()
    {
        $monthly_interest_rate = $this->interest_rate / 12;
        $credit_term = $this->credit_term;
        $numerator = $monthly_interest_rate * pow((1 + $monthly_interest_rate),$credit_term);
        $denominator =  pow((1 + $monthly_interest_rate),$credit_term) - 1;
        $res = $numerator / $denominator;
        return $res;
    }

    public function getMonthlyPayment()
    {
        return $this->getAnnuityRatio() * $this->sum;
    }

    public function calculateAllSumOfCredit()
    {
        return $this->getMonthlyPayment() * $this->credit_term;
    }


    public function prePersist()
    {
        $this->amount_with_costing = $this->calculateAllSumOfCredit();
    }


    /**
     * @return bool
     */
    public function isPaid()
    {
        if(!$this->getRemainder()) {
            return true;
        }
        return false;
    }
}
