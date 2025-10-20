<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_valable = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Products $products = null;

    #[ORM\Column(nullable: true)]
    private ?bool $activation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function isValable(): ?bool
    {
        return $this->is_valable;
    }

    public function setIsValable(?bool $is_valable): static
    {
        $this->is_valable = $is_valable;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProduits(): ?Products
    {
        return $this->products;
    }

    public function setProduits(?Products $produits): static
    {
        $this->products = $produits;

        return $this;
    }

    public function isActivation(): ?bool
    {
        return $this->activation;
    }

    public function setActivation(?bool $activation): static
    {
        $this->activation = $activation;

        return $this;
    }
}

