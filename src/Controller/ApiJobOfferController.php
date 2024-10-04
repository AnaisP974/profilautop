<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobSearchType;
use App\Form\FranceTravailType;
use App\Service\IndeedSearchService;
use App\Service\FranceTravailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiJobOfferController extends AbstractController
{
    private $indeedSearchService;
    private $franceTravailService;

    public function __construct(IndeedSearchService $indeedSearchService, FranceTravailService $franceTravailService)
    {
        $this->franceTravailService = $franceTravailService;
        $this->indeedSearchService = $indeedSearchService;
    }
    
    #[Route('/api/job-offer', name: 'app_api_jobOffer_search', methods:['GET','POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobOffer = new JobOffer();
        
        $indeedForm = $this->createForm(JobSearchType::class, $jobOffer);
        $indeedForm->handleRequest($request);

        if ($indeedForm->isSubmitted() && $indeedForm->isValid()) {
            $data = $indeedForm->getData();
            $jobTitle = $data->getTitle();
            $location = $data->getLocation();

            // Recherche sur Indeed
            $results = $this->indeedSearchService->searchJobs($jobTitle, $location);

            return $this->json($results); // Retourne les résultats au format JSON
        }

        $franceTravailForm = $this->createForm(FranceTravailType::class, $jobOffer);
        $franceTravailForm->handleRequest($request);

        if ($franceTravailForm->isSubmitted() && $franceTravailForm->isValid()) {
            $data = $franceTravailForm->getData();
            $jobTitle = $data->getTitle();
            // $selectedDepartment = $data['location'];
            $location = $data->getLocation();
            $location .= 'D';

            // Recherche sur france travail
            $results = $this->franceTravailService->searchJobs($jobTitle, $location);
            $response = $this->json($results);
            return dd($response); // Retourne les résultats au format JSON
        }

        return $this->render('api_job_offer/search.html.twig', [
            'jobOffer' => $jobOffer,
            'form' => $indeedForm,
            'franceTravailForm' => $franceTravailForm,
        ]);
    }
    #[Route('/api/job-offer/results', name: 'app_api_jobOffer_results', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('api_job_offer/results.html.twig', [
            'controller_name' => 'ApiJobOfferController',
        ]);
    }
}
