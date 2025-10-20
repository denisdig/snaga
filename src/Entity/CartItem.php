<?php
namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    // Vers quel panier cet item appartient
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: "items")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Cart $cart = null;

    // Le produit (j'assume App\Entity\Product)
    #[ORM\ManyToOne(targetEntity: Products::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Products $product = null;

    // Quantité
    #[ORM\Column(type: "integer")]
    private int $quantity = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;
        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(Products $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        // validation minimale : quantité >= 1
        $this->quantity = max(1, $quantity);
        return $this;
    }

    // utilitaire : sous-total de cet item
    public function getSubtotal(): float
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }
}
