<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy:"items")]
    #[ORM\JoinColumn(nullable:false)]
    private ?Order $order = null;

    #[ORM\ManyToOne(targetEntity: Products::class)]
    #[ORM\JoinColumn(nullable:false)]
    private ?Products $product = null;

    #[ORM\Column(type:"integer")]
    private int $quantity = 1;

    #[ORM\Column(type:"float")]
    private float $price = 0.0;

    // -------------------- GETTERS & SETTERS --------------------
    public function getId(): ?int { return $this->id; }

    public function getOrder(): ?Order { return $this->order; }
    public function setOrder(Order $order): static { $this->order = $order; return $this; }

    public function getProduct(): ?Products { return $this->product; }
    public function setProduct(Products $product): static { $this->product = $product; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): static { $this->price = $price; return $this; }
}
