<?php
namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Cart
{
    // id primaire
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    // Relation vers l'utilisateur (nullable pour les paniers invités si besoin)
    // J'utilise ManyToOne pour rester simple : un utilisateur peut avoir plusieurs paniers
    // historiques, mais typiquement on n'en garde qu'un "actif".
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    private ?User $user = null;

    // Les items du panier (collection de CartItem)
    #[ORM\OneToMany(mappedBy: "cart", targetEntity: CartItem::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $items;

    // Dates de création / mise à jour
    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    // --- lifecycle callbacks pour tenir à jour les dates ---
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    // --- getters / setters basiques ---
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

    /**
     * @return Collection|CartItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    // Ajoute un item : si le produit existe déjà, on incrémente la quantité
    public function addItem(CartItem $item): self
    {
        // si le produit est déjà dans le panier, on augmente la quantité
        foreach ($this->items as $existing) {
            if ($existing->getProduct()->getId() === $item->getProduct()->getId()) {
                $existing->setQuantity($existing->getQuantity() + $item->getQuantity());
                return $this;
            }
        }

        // sinon on rattache l'item et on l'ajoute
        $item->setCart($this);
        $this->items->add($item);
        return $this;
    }

    // Supprime un item du panier
    public function removeItem(CartItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // l'orphanRemoval s'occupe de supprimer l'entité si nécessaire
        }
        return $this;
    }

    // Calcule le total du panier (en euros, selon getPrice() du produit)
    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->getQuantity() * $item->getProduct()->getPrice();
        }
        return $total;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }
}
