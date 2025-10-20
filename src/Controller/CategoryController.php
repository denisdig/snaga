<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index')]
    public function index(CategoryRepository $repository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $repository->findAll()
        ]);
    }

    #[Route('/ajouter', name: 'app_category_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès');
            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('category/ajouter.html.twig', [
            'formCategory' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_category_edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès');
            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('category/form.html.twig', [
            'formCategory' => $form->createView(),
            'category' => $category
        ]);
    }

    #[Route('/supprimer/{id}', name: 'app_category_delete')]
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès');
        return $this->redirectToRoute('app_category_index');
    }

    #[Route('/fiche/{id}', name: 'app_category_show')]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }
}
