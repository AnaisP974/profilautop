<?php

namespace App\Controller;

use App\Enum\JobStatus;
use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Form\JobSearchType;
use App\Service\IndeedSearchService;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/job-offer')]
final class JobOfferController extends AbstractController
{
    private $indeedSearchService;

    public function __construct(IndeedSearchService $indeedSearchService)
    {
        $this->indeedSearchService = $indeedSearchService;
    }

    #[Route(name: 'app_job_offer_index', methods: ['GET'])]
    public function index(Request $request, JobOfferRepository $jobOfferRepository, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Récupérer la valeur du filtre de statut dans la requête GET
        $status = $request->query->get('filter');

        // Si un statut est sélectionné et correspond à l'énumération
        if ($status && JobStatus::tryFrom($status)) {
            // Récupérer les offres d'emploi par utilisateur et statut
            $jobOffers = $jobOfferRepository->findByUserAndStatus($user, $status);
        } else {
            // Sinon, récupérer toutes les offres d'emploi de l'utilisateur
            $jobOffers = $jobOfferRepository->findByUser($user);
        }

        // Rendre la vue avec les offres d'emploi filtrées
        return $this->render('job_offer/list.html.twig', [
            'job_offers' => $jobOffers,
            'selected_status' => $status,
        ]);
    }


    #[Route('/new', name: 'app_job_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setAppUser($user);
            $entityManager->persist($jobOffer);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job_offer/new.html.twig', [
            'jobOffer' => $jobOffer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_offer_show', methods: ['GET'])]
    public function show(JobOffer $jobOffer): Response
    {
        return $this->render('job_offer/show.html.twig', [
            'jobOffer' => $jobOffer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_offer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobOffer $jobOffer, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        // Création du formulaire pour éditer l'offre d'emploi
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont déjà mappées dans l'entité JobOffer
            // Donc pas besoin de récupérer explicitement `getData()`
            $jobOffer->setAppUser($user);
            // `getStatus()` retourne une instance de JobStatus Enum
            $status = $jobOffer->getStatus()->value;
            
            // Vérification simple en utilisant les cas de l'enum, sans conversion manuelle
            if ($status === "En attente") {
                $jobOffer->setApplicationDate(new \DateTime());
            } 

            // Pas besoin de convertir les valeurs de l'enum en chaîne ici

            // Sauvegarde de l'offre modifiée dans la base de données
            $entityManager->flush();

            // Redirection après la soumission réussie du formulaire
            return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affichage du formulaire pour l'édition
        return $this->render('job_offer/edit.html.twig', [
            'jobOffer' => $jobOffer,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_job_offer_delete', methods: ['POST'])]
    public function delete(Request $request, JobOffer $jobOffer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobOffer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($jobOffer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
    }
}
