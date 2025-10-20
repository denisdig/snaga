<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();
        return $this->render('admin/index.html.twig', [
             'products' => $products
        ]);
    }
}
