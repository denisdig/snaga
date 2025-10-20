<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private CartService $cartService;
    private EntityManagerInterface $em;

    public function __construct(CartService $cartService, EntityManagerInterface $em)
    {
        $this->cartService = $cartService;
        $this->em = $em;
    }

    // =============================
    // Créer une commande à partir du panier
    // =============================
    #[Route('/order/create', name: 'order_create')]
    public function create(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour passer une commande.');
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le panier
        $cart = $this->cartService->getCart($user);

        if (count($cart->getItems()) === 0) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('product_index');
        }

        // Créer la commande
        $order = new Order();
        $order->setUser($user);
        $order->setTotal($cart->getTotal());

        foreach ($cart->getItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setPrice($cartItem->getProduct()->getPrice());

            $this->em->persist($orderItem);
        }

        $this->em->persist($order);
        $this->em->flush();

        // Vider le panier
        $this->cartService->clear($user);

        return $this->redirectToRoute('order_success');
    }

    // =============================
    // Page commande réussie
    // =============================
    #[Route('/order/success', name: 'order_success')]
    public function success(): Response
    {
        return $this->render('order/success.html.twig');
    }

    #[Route('/mes-commandes', name: 'order_list')]
    public function list(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour voir vos commandes.');
            return $this->redirectToRoute('app_login');
        }

        // Récupère toutes les commandes de l'utilisateur
        $orders = $orderRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('order/list.html.twig', [
            'orders' => $orders
        ]);
    }
}
