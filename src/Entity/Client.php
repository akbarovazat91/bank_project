<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->credits = new ArrayCollection();
    }


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Credit", mappedBy="client")
     */
    private $credits;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="client")
     */
    private $payments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getStatusName(): ?string
    {
        return ($this->status) ? "Активен" : "Неактивен";
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPayments()
    {
        return $this->payments;
    }


    /**
     * @return bool
     */
    public function lastCreditIsPaid()
    {
        $all_credits = $this->credits;
        if(!count($all_credits)) {
          return true;
        }

        $last_credit = $all_credits->last();
        if($last_credit->isPaid()) {
            return true;
        }

        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getCredits(): ArrayCollection
    {
        return $this->credits;
    }

}
