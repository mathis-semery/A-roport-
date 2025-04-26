<?php

namespace App\Controller;

use App\Entity\Conges;
use App\Entity\Utilisateur;

use App\Form\CongesDemandeType;
use App\Form\CongesType;
use App\Repository\CongesRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/conges')]
class CongesController extends AbstractController
{
    #[Route('/', name: 'app_conges_index', methods: ['GET'])]
    public function index(CongesRepository $congesRepository): Response
    {
        return $this->render('conges/index.html.twig', [
            'conges' => $congesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_conges_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conges = new Conges();
        $form = $this->createForm(CongesType::class, $conges);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conges);
            $entityManager->flush();

            return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conges/new.html.twig', [
            'conges' => $conges,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conges_show', methods: ['GET'])]
    public function show(Conges $conges): Response
    {
        return $this->render('conges/show.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conges_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conges $conges, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongesType::class, $conges);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $conges->setValidateur($this->getUser());
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conges/edit.html.twig', [
            'conges' => $conges,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_conges_delete', methods: ['POST'])]
    public function delete(Request $request, Conges $conges, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conges->getId(), $request->request->get('_token'))) {
            $entityManager->remove($conges);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/demandeConges/{id}', name: 'app_conges_demande')]
    public function demande(Request $request, EntityManagerInterface $em, UtilisateurRepository $userRepository, int $id): Response
    {
        // Récupère l'utilisateur par son ID
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Crée une nouvelle demande de congé
        $conges = new Conges();
        $form = $this->createForm(CongesDemandeType::class, $conges);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associe la demande de congé à l'utilisateur
            $conges->setUtilisateur($user);  // Assure-toi que ton entité Conges a une relation avec Utilisateur

            // Le congé est non validé par défaut
            $conges->setEstValide(false);

            // Sauvegarde la demande de congé
            $em->persist($conges);
            $em->flush();

            // Affiche un message de succès
            $this->addFlash('success', 'Votre demande de congé a été envoyée !');

            // Redirige vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // Affiche le formulaire pour demander un congé
        return $this->render('conges/demande.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/mesConges/{id}', name: 'app_conges_mesConges', methods: ['GET'])]
    public function mesConges(int $id, CongesRepository $congesRepository): Response
    {
        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            // Si l'utilisateur n'est pas connecté, redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Vérifier que l'ID passé correspond à l'utilisateur connecté
        if ($user->getId() !== $id) {
            // Si l'ID de l'utilisateur ne correspond pas à l'ID de la route, redirige vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // Récupérer toutes les demandes de congé de cet utilisateur
        $conges = $congesRepository->findBy(['utilisateur' => $user]);

        // Rendre la page avec les données des congés
        return $this->render('conges/mesConges.html.twig', [
            'conges' => $conges,
        ]);
    }

}
