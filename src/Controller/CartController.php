<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Products;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // =============================
    // Afficher le panier
    // =============================
    #[Route('/cart', name: 'cart_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        $cart = $this->cartService->getCart($user);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $cart->getTotal(),
        ]);
    }

    // =============================
    // Ajouter un produit
    // =============================
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Products $product, Request $request): Response
    {
        $quantity = max(1, (int)$request->query->get('quantity', 1));
        $this->cartService->addProduct($product, $quantity, $this->getUser());

        $this->addFlash('success', $product->getName() . ' ajouté au panier !');

        return $this->redirectToRoute('cart_index');
    }

    // =============================
    // Supprimer un produit
    // =============================
    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(Products $product): Response
    {
        $this->cartService->removeProduct($product, $this->getUser());
        $this->addFlash('success', $product->getName() . ' supprimé du panier !');

        return $this->redirectToRoute('cart_index');
    }

    // =============================
    // Mettre à jour la quantité
    // =============================
    #[Route('/cart/update/{id}', name: 'cart_update', methods:['POST'])]
    public function update(Products $product, Request $request): Response
    {
        $quantity = max(1, (int)$request->request->get('quantity', 1));
        $this->cartService->updateQuantity($product, $quantity, $this->getUser());

        return $this->redirectToRoute('cart_index');
    }

    // =============================
    // Vider le panier
    // =============================
    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(): Response
    {
        $this->cartService->clear($this->getUser());
        $this->addFlash('success', 'Panier vidé !');

        return $this->redirectToRoute('cart_index');
    }
}
