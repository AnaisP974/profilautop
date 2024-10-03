<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiJobOfferController extends AbstractController
{
    #[Route('/api/job-offer/update-status', name: 'app_api_jobOffer_updateStatus', methods:['POST'])]
    public function index(): Response
    {
        return $this->render('api_job_offer/index.html.twig', [
            'controller_name' => 'ApiJobOfferController',
        ]);
    }
}
