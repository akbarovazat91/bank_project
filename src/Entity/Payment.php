<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="payments")
     *
     */
    private $client;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Credit", inversedBy="payments")
     *
     */
    private $credit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    private $sum;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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
     * @param Client $credit
     * @return Payment
     */
    public function setCredit(Client $credit): Payment
    {
        $this->credit = $credit;
        return $this;
    }

    /**
     * @return Client
     */
    public function getCredit(): Client
    {
        return $this->credit;
    }
}
