<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il y a deja un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer une adresse email valide.")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    // =============================
    // Relation avec AdresseLivraison (plusieurs adresses possibles)
    // =============================
    #[ORM\OneToMany(targetEntity: AdresseLivraison::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $adresse_livraison;

    public function __construct()
    {
        $this->adresse_livraison = new ArrayCollection();
    }

    // -------------------- GETTERS & SETTERS --------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporairement, les effacer ici
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    // =============================
    // Méthodes pour AdresseLivraison
    // =============================
    /**
     * @return Collection|AdresseLivraison[]
     */
    public function getAdresseLivraison(): Collection
    {
        return $this->adresse_livraison;
    }

    public function addAdresseLivraison(AdresseLivraison $adresseLivraison): self
    {
        if (!$this->adresse_livraison->contains($adresseLivraison)) {
            $this->adresse_livraison[] = $adresseLivraison;
            $adresseLivraison->setUser($this);
        }
        return $this;
    }

    public function removeAdresseLivraison(AdresseLivraison $adresseLivraison): self
    {
        if ($this->adresse_livraison->removeElement($adresseLivraison)) {
            if ($adresseLivraison->getUser() === $this) {
                $adresseLivraison->setUser(null);
            }
        }
        return $this;
    }
}
