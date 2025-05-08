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

        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }


        $conges = new Conges();
        $form = $this->createForm(CongesDemandeType::class, $conges);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $conges->setUtilisateur($user);


            $conges->setEstValide(null);


            $em->persist($conges);
            $em->flush();

            $this->addFlash('success', 'Votre demande de congé a été envoyée !');

            return $this->redirectToRoute('app_home');
        }


        return $this->render('conges/demande.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/mesConges/{id}', name: 'app_conges_mesConges', methods: ['GET'])]
    public function mesConges(int $id, CongesRepository $congesRepository): Response
    {

        $user = $this->getUser();
        if (!$user) {

            return $this->redirectToRoute('app_login');
        }


        if ($user->getId() !== $id) {

            return $this->redirectToRoute('app_home');
        }


        $conges = $congesRepository->findBy(['utilisateur' => $user]);


        return $this->render('conges/mesConges.html.twig', [
            'conges' => $conges,
        ]);
    }

}
