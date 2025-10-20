<?php
namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository basique pour Cart.
 *
 * Remarque : MakerBundle génère ce fichier automatiquement, mais on peut le créer à la main.
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    // Méthode utilitaire pour ajouter (persist) un Cart
    public function add(Cart $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Méthode utilitaire pour supprimer un Cart
    public function remove(Cart $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Exemple : trouver le panier "actif" d'un utilisateur (à adapter selon ton User)
    // public function findActiveCartForUser(User $user): ?Cart { ... }
}
