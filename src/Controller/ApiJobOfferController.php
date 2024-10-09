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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ApiJobOfferController extends AbstractController
{
    // private $indeedSearchService;
    // private $franceTravailService;

    // public function __construct(IndeedSearchService $indeedSearchService, FranceTravailService $franceTravailService)
    // {
    //     $this->franceTravailService = $franceTravailService;
    //     $this->indeedSearchService = $indeedSearchService;
    // }

    // #[Route('/api/job-offer', name: 'app_api_jobOffer_search', methods:['GET','POST'])]
    // public function search(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $jobOffer = new JobOffer();

    //     $indeedForm = $this->createForm(JobSearchType::class, $jobOffer);
    //     $indeedForm->handleRequest($request);

    //     if ($indeedForm->isSubmitted() && $indeedForm->isValid()) {
    //         $data = $indeedForm->getData();
    //         $jobTitle = $data->getTitle();
    //         $location = $data->getLocation();

    //         // Recherche sur Indeed
    //         $results = $this->indeedSearchService->searchJobs($jobTitle, $location);

    //         return $this->json($results); // Retourne les résultats au format JSON
    //     }

    //     $franceTravailForm = $this->createForm(FranceTravailType::class, $jobOffer);
    //     $franceTravailForm->handleRequest($request);

    //     if ($franceTravailForm->isSubmitted() && $franceTravailForm->isValid()) {
    //         $data = $franceTravailForm->getData();
    //         $jobTitle = $data->getTitle();
    //         // $selectedDepartment = $data['location'];
    //         $location = $data->getLocation();
    //         $location .= 'D';

    //         // Recherche sur france travail
    //         $results = $this->franceTravailService->searchJobs($jobTitle, $location);
    //         $response = $this->json($results);
    //         return dd($response); // Retourne les résultats au format JSON
    //     }

    //     return $this->render('api_job_offer/search.html.twig', [
    //         'jobOffer' => $jobOffer,
    //         'form' => $indeedForm,
    //         'franceTravailForm' => $franceTravailForm,
    //     ]);
    // }


    #[Route('/api/job-offer/{id}/cover_letter', name: 'api_jobOffer_cover_letter', methods: ['GET', 'POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager, JobOffer $jobOffer): Response
    {
        // Vérifiez si la méthode de la requête est POST
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $jobName = $request->request->get('job_name');
            $jobCompany = $request->request->get('job_company');
            $jobCity = $request->request->get('job_city');
            $motivation = $request->request->get('motivation');
            $competence = $request->request->get('competence');
            $style = $request->request->get('style');

            // Préparer les données pour la requête API
            $data = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "Voici une lettre de motivation pour le poste de $jobName chez $jobCompany situé à $jobCity. Motivations: $motivation. Compétences: $competence. Style: $style."
                            ]
                        ]
                    ]
                ]
            ];

            // Appeler l'API pour générer la lettre de motivation
            $response = $this->callApi($data);
            
            dd($response); 
            
            // Traiter la réponse de l'API
            return $this->render('api_job_offer/cover_letter_api.html.twig', [
                'jobOffer' => $jobOffer,
                'generatedLetter' => $response // Vous pouvez envoyer la lettre générée à votre vue
            ]);
        }

        return $this->render('api_job_offer/cover_letter_api.html.twig', [
            'jobOffer' => $jobOffer,
        ]);
    }

    // Fonction pour appeler l'API
    private function callApi(array $data)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=AIzaSyC6USOQXwXUTBO4f0cZvGEVllpmTdZp3s8', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);
        // $responseData = json_decode($response->getBody(), true);
        // dump($responseData); 
        return json_decode($response->getBody(), true); // Décoder la réponse JSON
    }


    // #[Route('/api/job-offer/results', name: 'app_api_jobOffer_results', methods: ['GET'])]
    // public function index(): Response
    // {
    //     return $this->render('api_job_offer/results.html.twig', [
    //         'controller_name' => 'ApiJobOfferController',
    //     ]);
    // }
}
