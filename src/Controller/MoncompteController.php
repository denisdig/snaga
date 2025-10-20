<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InfonewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MoncompteController extends AbstractController
{
    #[Route('/moncompte', name: 'app_moncompte')]
    public function index(): Response
    {
        return $this->render('moncompte/index.html.twig', [
            
        ]);
    }

    #[Route('/moncompte/info/', name: 'app_info')]
    public function info(): Response
    {
        return $this->render('moncompte/info.html.twig', [
            
        ]);
    }

    #[Route('/moncompte/infonvl/{id}', name: 'app_infonvl')]
    public function infonew($id, Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        
        $form=$this->createForm(InfonewType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('moncompte/infonew.html.twig', [
            'form' => $form
        ]);
    }



    #[Route('/moncomptesupp/{id}', name: 'app_infosupp')]
    public function infosup($id, EntityManagerInterface $entityManager): Response
    {

        $user = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
        

    }


    
}
