<?php

namespace App\Controller;


use App\Entity\Products;

use App\Form\ProductsForm;

use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/product')]
/*
    Préfixe des routes,
    Toutes les routes de ce controller commenceront par /produit
*/
final class ProductsController extends AbstractController
{

    #[Route('/afficher', name:'app_product_index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        /*
            Lorsqu'une entity est créée, son repository est généré automatique
            Le Repository d'une Entity permet d'éffectuer des requêtes SELECT

            Cette route va dépendre du ProductRepository

            Dans  les parenthèses de la méthode de la route, on y place des dépendances

            Syntaxe pour appeler des objets en dépendance :
            Class $objet
        */

        $products = $productsRepository->findAll(); 
        /*
            findAll() ---> SELECT * FROM product
            La méthode findAll() retourne un tableau
        */
        
        /*
            $product = $productRepository->find(10);
            find(int $id) ----> SELECT * FROM product WHERE id = $id
            La méthode find() retourne un objet Product (ou null)
        */
        //dd($products);
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/ajouter', name:'app_product_ajouter')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // Création d'un objet issu de la class Product (entity)
        $product = new Products();

        
        // dump($product);

        /*
            Création du formulaire par le biais de la méthode createForm() provenant de la class AbstractController

            1e argument : nom de la class Type contenant l'objet $builder (le plan de construction du formulaire)
            2e argument : objet issu de la class (Entity)

            Cette méthode retourne un objet de la class FormInterface

        */
        $form = $this->createForm(ProductsForm::class, $product);

       
        // traitement du formulaire
        $form->handleRequest($request);

        // Si le formulaire a été soumis (click sur le bouton submit)
        // Si le formulaire respecte les conditions/constraints
        if ($form->isSubmitted() && $form->isValid()) {


            $pictureFile = $form->get('picture')->getData(); // recupere les informations de l'image

            //dd($pictureFile);

            /*
                S'il n'y a pas d'image chargée (formulaire) alors $pictureFile est null
                S'il y a une image chargée (formulaire) alors $pictureFile n'est pas null, il est un objet de la class UploadedFile

            */
            // traitement de l'image s'il y en a une
            if ($pictureFile) {

                // 1e : Définir le nom du fichier
                    $pictureFileName = date('YmdHis') . '-' . rand(1000,9999) . '-' . $pictureFile->getClientOriginalName();//on redonne un nom à l'image
                    // YYYYmmddHHiiss
                    //dd($pictureFileName);

                // 2e : Enregistrer le fichier image dans le dossier public

                    $pictureFile->move( // on deplace la photo dans le dossier
                        $this->getParameter('picture_parameter'), 
                        $pictureFileName
                    );
                    // move : 2 arguments
                    // 1e : emplacement (services.yaml)
                    /*
                        config/services.yaml
                        parameters: changer la page config/services.yaml
                            picture_parameter: '%kernel.project_dir%/public/image/product'

                    */
                    // 2e : nom du fichier

                // 3e : Enregistrer le nom du fichier dans l'objet $product
                    $product->setPicture($pictureFileName);
            }

            $em->persist($product); // INSERT INTO 
            $em->flush();
            // dd($product);

            // notification
            $this->addFlash('success', 'Le produit a bien été ajouté');
            // redirection
            return $this->redirectToRoute('app_product_index');
        }


        return $this->render('product/ajouter.html.twig', [
            'formProduct' => $form->createView(), // création de la vue html du formulaire
        ]);
    }

    #[Route('/fiche/{id}', name:'app_product_show')]
    public function show(Products $product): Response
    {
        /*
            pour récupérer un paramètre de l'url, dans la route, la syntaxe est avec des accolades {}
            la valeur placée est le nom du paramètre 
            si celui-ci est une valeur d'une propriété d'un objet, il faut respecter le nom de cette propriété

            En dépendance, on génère un objet issu de la class Entity, le paramètre de l'url va se positionner sur la propriété qui porte la même dénomination.

            Avec Doctrine, les informations du produit provenant de la BDD seront récupérées dans l'objet

        */
        return $this->render('product/show.html.twig',[
            'product' => $product
        ]);
    }

    #[Route('/modifier/{id}', name:'app_product_edit')]
    public function edit(Products $product, Request $request, EntityManagerInterface $em): Response
    {
        /*
            On observe qu'il y a le même 'code' entre ajouter et modifier
            la SEULE différence est que lorsqu'on veut ajouter un produit, on génère un nouvel objet $product (new) alors que lorsqu'on veut modifier un produit on a un objet en dépendance qui est créé via le paramètre dans l'URL
        */
        $form = $this->createForm(ProductsForm::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            $this->addFlash('success', 'Le produit a bien été modifié');
            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formProduct' => $form->createView()
        ]);
    }

    #[Route('/supprimer/{id}', name:'app_product_delete')]
    public function delete(Products $product, EntityManagerInterface $em): Response
    {
        $em->remove($product);
        $em->flush();
        $this->addFlash('success', 'Le produit a bien été supprimé');
        return $this->redirectToRoute('app_product_index');
    }
    


}
