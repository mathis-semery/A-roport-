<?php

namespace App\Controller;

use App\Entity\Modele;
use App\Form\ModeleType;
use App\Repository\ModeleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class ModeleController extends AbstractController
{
    #[Route('/modele', name: 'app_modele')]
    public function index(ModeleRepository $modeleRepository){
        $modeles = $modeleRepository->findAll();
        return $this->render('modele/tableau.html.twig', [
            'modeles' => $modeles,
        ]);
    }


    #[Route('/modele/new', name: 'app_modele_new')]
    public function new(\Symfony\Component\HttpFoundation\Request $request ,EntityManagerInterface $entityManager): Response
    {
        $modele = new Modele();
        $formulaire = $this->createForm(ModeleType::class, $modele);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $entityManager->persist($modele);
            $entityManager->flush();
            return $this->redirectToRoute('app_modele');
        }
        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
            'formulaire' => $formulaire->createView(),
        ]);
    }

    #[Route('/modele/{id}/edit', name: 'app_modele_edit')]
    public function edit($id ,\Symfony\Component\HttpFoundation\Request $request ,EntityManagerInterface $entityManager , ModeleRepository $modeleRepository): Response
    {
        $modele = $modeleRepository->find($id);
        $formulaire = $this->createForm(ModeleType::class, $modele);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $entityManager->persist($modele);
            $entityManager->flush();
        }
        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
            'formulaire' => $formulaire->createView(),
        ]);
    }
    #[Route('/modele/{id}/delete', name: 'app_modele_delete')]
    public function delete(Modele $modele , EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($modele);
        $entityManager->flush();
        return $this->redirectToRoute('app_modele');
    }
}
