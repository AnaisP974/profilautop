<?php

namespace App\Controller;

use App\Enum\JobStatus;
use App\Entity\JobOffer;
use App\Form\JobStatusType;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class KanbanController extends AbstractController
{
    #[Route('/kanban', name: 'app_kanban', methods:['GET'])]
    public function index(JobOfferRepository $jobOfferRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->render('kanban/index.html.twig', [
            'job_offers' => $jobOfferRepository->findAll(),
            'job_offer_status' => JobStatus::class
        ]);
    }

    #[Route('/kanban/edit/{id}', name: 'app_kanban_edit', methods: ['GET', 'POST'])]
    public function edit(JobOffer $jobOffer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobStatusType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status = $jobOffer->getStatus()->value;

            if ($status === "En attente") {
                $jobOffer->setApplicationDate(new \DateTime());
            } 

            $entityManager->flush();

            return $this->redirectToRoute('app_kanban', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('kanban/edit.html.twig', [
            'job_offer' => $jobOffer,
            'job_offer_status' => JobStatus::class,
            'statusForm' => $form
        ]);
    }
}
