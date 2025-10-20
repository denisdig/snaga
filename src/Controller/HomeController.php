<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, ProductsRepository $productsRepository): Response
    {

        $category = $categoryRepository->findAll();
        $products = $productsRepository->findAll();
        return $this->render('home/index.html.twig', [
            'category'=>$category,
            'products'=>$products
        ]);
    }
        #[Route('/lorem', name: 'app_lorem')]
    public function apropos(): Response
    {
        return $this->render('home/lorem.html.twig');
    }
}

