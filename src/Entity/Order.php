<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: "orders")] // <-- table renommée pour éviter le mot réservé "order"
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable:false)]
    private ?User $user = null;

    #[ORM\Column(type:"float")]
    private float $total = 0.0;

    #[ORM\Column(type:"datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type:"string", length:50)]
    private string $status = 'validee';

    #[ORM\OneToMany(mappedBy:"order", targetEntity: OrderItem::class, cascade:["persist"])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    // -------------------- GETTERS & SETTERS --------------------
    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(User $user): static { $this->user = $user; return $this; }

    public function getTotal(): float { return $this->total; }
    public function setTotal(float $total): static { $this->total = $total; return $this; }

    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection { return $this->items; }
    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $item->setOrder($this);
            $this->items->add($item);
        }
        return $this;
    }
}
