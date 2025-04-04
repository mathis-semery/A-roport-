<?php

namespace App\Controller;

use App\Entity\Vol;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\VolRepository;
use Symfony\Component\Routing\Attribute\Route;


final class VolController extends AbstractController
{
    #[Route('/vol', name: 'app_vol')]
    public function index(): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }

    #[Route('/vol/{id}', name: 'vol_detail')]
    public function detail(int $id): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController',
            'id' => $id,
        ]);
    }

    #[Route('/vol/ajout', name: 'vol_ajout')]
    public function ajoutVol(EntityManagerInterface $entityManager): Response
    {
        $vol = new Vol();
        $vol->setVilleDepart('Paris');
        $vol->setVilleArrive("Malaga");
        $vol->setDateDepart(new \DateTime("now"));
        $vol->setHeureDepart(new \DateTime("now"));
        $vol->setPrixBillet("250");


        $entityManager->persist($vol);
        $entityManager->flush();
        $entityManager->refresh($vol);
        dump($vol);

        return $this->render('vol/ajout.html.twig', [
            'controller_name' => 'VolController',
            'vol' => $vol,
        ]);
    }

    #[Route('/vols', name: 'liste_vols')]
    public function listeVols(VolRepository $volRepository): Response
    {
        $vols = $volRepository->findAll();
        return $this->render('vol/liste.html.twig', ['vols' => $vols]);
    }
}
