<?php

namespace App\Controller;

use App\Entity\Vol;
use App\Entity\Utilisateur;
use App\Form\VolType;
use App\Repository\VolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/vol')]
class VolController extends AbstractController
{
    private $httpClient;

    // Injecter le client HTTP pour faire des requêtes à l'API Unsplash
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/destination', name: 'app_vol_destination', methods: ['GET'])]
    public function destination(VolRepository $volRepository): Response
    {
        // Récupérer tous les vols
        $vols = $volRepository->findAll();

        // Pour chaque vol, récupérer l'image associée à la ville d'arrivée
        foreach ($vols as $vol) {
            $imageUrl = $this->getCityImage($vol->getVilleArrive());
            $vol->setCityImageUrl($imageUrl); // On peut ajouter une propriété 'cityImageUrl' à l'entité Vol pour stocker l'URL de l'image
        }

        return $this->render('vol/destination.html.twig', [
            'vols' => $vols,
        ]);
    }
    #[Route('/', name: 'app_vol_index', methods: ['GET'])]
    public function index(VolRepository $volRepository): Response
    {
        // On récupère tous les vols
        $vols = $volRepository->findAll();

        // Pour chaque vol, on récupère une image de la ville d'arrivée
        foreach ($vols as $vol) {
            $imageUrl = $this->getCityImage($vol->getVilleArrive());
            $vol->setCityImageUrl($imageUrl); // On stocke l'URL de l'image dans l'objet vol
        }

        return $this->render('vol/index.html.twig', [
            'vols' => $vols,
        ]);
    }

    #[Route('/new', name: 'app_vol_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vol = new Vol();

        // Récupérer les utilisateurs ayant le métier 'pilote'
        $pilotes = $entityManager->getRepository(Utilisateur::class)->findBy(['metier' => 'pilote']);

        // Créer le formulaire pour le vol et passer les pilotes filtrés
        $form = $this->createForm(VolType::class, $vol, [
            'pilotes' => $pilotes,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vol);
            $entityManager->flush();

            return $this->redirectToRoute('app_vol_index');
        }

        return $this->render('vol/new.html.twig', [
            'vol' => $vol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vol_show', methods: ['GET'])]
    public function show(Vol $vol): Response
    {
        return $this->render('vol/show.html.twig', [
            'vol' => $vol,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les utilisateurs ayant le métier 'pilote'
        $pilotes = $entityManager->getRepository(Utilisateur::class)->findBy(['metier' => 'pilote']);

        $form = $this->createForm(VolType::class, $vol, [
            'pilotes' => $pilotes,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vol_index');
        }

        return $this->render('vol/edit.html.twig', [
            'vol' => $vol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vol_delete', methods: ['POST'])]
    public function delete(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vol->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vol_index');
    }

    // Fonction pour récupérer l'image de la ville via l'API Unsplash
    private function getCityImage(string $ville): string
    {
        $apiKey = 'PQsGkIcZ5KO2CunK_SO3JciivkHLLdIrs-8XT6Q8ibs';
        $url = 'https://api.unsplash.com/photos/random?query=' . urlencode($ville . ' city landscape') . '&client_id=' . $apiKey;

        try {
            $response = $this->httpClient->request('GET', $url);

            // Debug: Vérifiez le statut HTTP
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('API Unsplash non disponible');
            }

            $data = $response->toArray();

            // Debug: Loggez la réponse complète
            // file_put_contents('unsplash_response.json', json_encode($data));

            return $data['urls']['regular'] ?? $this->getDefaultImage();

        } catch (\Exception $e) {
            // Debug: Loggez l'erreur
            // file_put_contents('unsplash_error.log', $e->getMessage());
            return $this->getDefaultImage();
        }
    }
    private function getDefaultImage(): string
    {
        return 'https://via.placeholder.com/500x300.png?text=No+Image';
    }


}
