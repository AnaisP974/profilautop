<?php

namespace App\Controller;

use App\Enum\JobStatus;
use App\Entity\JobOffer;
use Psr\Log\LoggerInterface;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class KanbanController extends AbstractController
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/kanban', name: 'app_kanban', methods: ['GET'])]
    public function index(JobOfferRepository $jobOfferRepository, LoggerInterface $logger): Response
    {
        $jobOffers = $jobOfferRepository->findAll();
        $logger->info('Récupération des offres d\'emploi', ['count' => count($jobOffers)]);

        foreach ($jobOffers as $offer) {
            $logger->info('Détails de l\'offre', [
                'id' => $offer->getId(),
                'status' => $offer->getStatus()->value
            ]);
        }

        return $this->render('kanban/index.html.twig', [
            'job_offers' => $jobOffers,
            'job_offer_status' => JobStatus::cases(),
        ]);
    }

    #[Route('/update-job-status', name: 'update_job_status', methods: ['POST'])]
    public function updateJobStatus(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $jobOfferId = $data['jobOfferId'] ?? null;
        $newStatus = $data['newStatus'] ?? null;

        $logger->info('Tentative de mise à jour', ['jobOfferId' => $jobOfferId, 'newStatus' => $newStatus]);

        if (!$jobOfferId || !$newStatus) {
            return $this->json(['success' => false, 'message' => 'Données manquantes'], 400);
        }

        $jobOffer = $entityManager->getRepository(JobOffer::class)->find($jobOfferId);

        if (!$jobOffer) {
            return $this->json(['success' => false, 'message' => 'Offre d\'emploi non trouvée'], 404);
        }

        try {
            $status = JobStatus::from($newStatus);
            $jobOffer->setStatus($status);
            $entityManager->flush();
            $logger->info('Mise à jour réussie', ['jobOfferId' => $jobOfferId, 'newStatus' => $newStatus]);
            return $this->json(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } catch (\ValueError $e) {
            $logger->error('Erreur lors de la mise à jour', [
                'jobOfferId' => $jobOfferId,
                'newStatus' => $newStatus,
                'error' => $e->getMessage(),
                'validStatuses' => array_column(JobStatus::cases(), 'value')
            ]);
            return $this->json([
                'success' => false,
                'message' => 'Statut invalide',
                'validStatuses' => array_column(JobStatus::cases(), 'value')
            ], 400);
        }
    }
}
