<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AfficherController extends AbstractController
{

    #[Route('/afficher/{id}', name: 'app_afficher')]
    public function index(Products $products, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            
            $comment->setUser($this->getUser());
            $comment->setProduits($products);
            $comment->setCreatedAt(new \DateTimeImmutable('now'));

            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Merci pour votre commentaire, il sera traîté dans les plus brefs délais');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('afficher/index.html.twig', [
            'products'=>$products,
            'form' => $form->createView(),
            'comment'=>$comment
        ]);
    }

    #[Route('/voir/{id}', name: 'app_afficher_category')]
    public function afficher($id,CategoryRepository $categoryRepository, ProductsRepository $productsRepository): Response
    {
        $category = $categoryRepository->findAll();
        $products = $productsRepository->findBy(["category"=>$id]);

        return $this->render('afficher/indexcategory.html.twig', [
            'products'=>$products,
            'category'=>$category,
        ]);
    }
}
