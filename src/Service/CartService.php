<?php
namespace App\Service;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\CartItem;
use App\Entity\Products;
use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private EntityManagerInterface $em;
    private CartRepository $cartRepository;
    private CartItemRepository $cartItemRepository;
    private SessionInterface $session;

    private const SESSION_CART_KEY = 'cart';

    public function __construct(
        EntityManagerInterface $em,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        RequestStack $requestStack
    ) {
        $this->em = $em;
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->session = $requestStack->getSession();
    }

    // =============================
    // Récupérer le panier actuel
    // =============================
    public function getCart(?User $user = null): Cart
    {
        if ($user) {
            // Utilisateur connecté → chercher son panier en base
            $cart = $this->cartRepository->findOneBy(['user' => $user]);
            if (!$cart) {
                $cart = new Cart();
                $cart->setUser($user);
                $this->em->persist($cart);
                $this->em->flush();
            }
            return $cart;
        } else {
            // Visiteur non connecté → panier en session
            $cart = $this->session->get(self::SESSION_CART_KEY);
            if (!$cart) {
                $cart = new Cart(); // panier temporaire (non persisté)
                $this->session->set(self::SESSION_CART_KEY, $cart);
            }
            return $cart;
        }
    }

    // =============================
    // Ajouter un produit
    // =============================
    public function addProduct(Products $product, int $quantity = 1, ?User $user = null): void
    {
        $cart = $this->getCart($user);

        $item = new CartItem();
        $item->setProduct($product)
             ->setQuantity($quantity);

        $cart->addItem($item);

        if ($user) {
            // Persist en BDD
            $this->em->persist($item);
            $this->em->persist($cart);
            $this->em->flush();
        } else {
            // Mettre à jour la session
            $this->session->set(self::SESSION_CART_KEY, $cart);
        }
    }

    // =============================
    // Supprimer un produit
    // =============================
    public function removeProduct(Products $product, ?User $user = null): void
    {
        $cart = $this->getCart($user);

        foreach ($cart->getItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $cart->removeItem($item);
                if ($user) {
                    $this->em->remove($item);
                    $this->em->flush();
                } else {
                    $this->session->set(self::SESSION_CART_KEY, $cart);
                }
                break;
            }
        }
    }

    // =============================
    // Mettre à jour la quantité
    // =============================
    public function updateQuantity(Products $product, int $quantity, ?User $user = null): void
    {
        $cart = $this->getCart($user);

        foreach ($cart->getItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $item->setQuantity($quantity);
                if ($user) {
                    $this->em->persist($item);
                    $this->em->flush();
                } else {
                    $this->session->set(self::SESSION_CART_KEY, $cart);
                }
                break;
            }
        }
    }

    // =============================
    // Vider le panier
    // =============================
    public function clear(?User $user = null): void
    {
        $cart = $this->getCart($user);
        foreach ($cart->getItems() as $item) {
            if ($user) {
                $this->em->remove($item);
            }
        }
        $cart->getItems()->clear();

        if ($user) {
            $this->em->flush();
        } else {
            $this->session->set(self::SESSION_CART_KEY, $cart);
        }
    }

    // =============================
    // Fusionner le panier session vers la BDD (après login)
    // =============================
    public function mergeSessionCartToDatabase(User $user): void
    {
        $sessionCart = $this->session->get(self::SESSION_CART_KEY);
        if (!$sessionCart) {
            return;
        }

        $dbCart = $this->getCart($user);

        foreach ($sessionCart->getItems() as $item) {
            $dbCart->addItem($item);
            $this->em->persist($item);
        }

        $this->em->persist($dbCart);
        $this->em->flush();

        // on vide la session
        $this->session->remove(self::SESSION_CART_KEY);
    }

    // =============================
    // Calculer le total
    // =============================
    public function getTotal(?User $user = null): float
    {
        $cart = $this->getCart($user);
        return $cart->getTotal();
    }
}
