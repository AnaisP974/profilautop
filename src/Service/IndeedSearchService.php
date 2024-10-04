<?php

namespace App\Service;

use GuzzleHttp\Client;

class IndeedSearchService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function searchJobs(string $jobTitle, string $location): array
    {
        $url = 'https://fr.indeed.com/jobs?';
        $query = [
            'q' => $jobTitle,
            'l' => $location,
            // 'from'=> 'searchOnHP',
            // 'Content-Type'=> 'application/x-www-form-urlencoded',
            // 'Accept' => 'application/json',
            // 'redirect_uri'=> 'http://www.acerecruitersllc.com/oauth/indeed?code=rXZSMNyYQHQ&state=employer1234',
            // 'client_id' => '7c9156c7c6eb93c987a1203117f3b9a859795a203b25f418502b4c46a81c1b35',
            // 'secret'=> 'o6WrcXZaBdisQya4KL8qLHeTH5aDTMXeO3RLCQqqvDRePlUgP1pXwGHsV8RRJswy'
        ];

        $response = $this->client->request('GET', $url, [
            'query' => $query,
            // 'verify' => false,
        ]);

        // Assurez-vous de vérifier le code de réponse HTTP
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        // Récupération du contenu
        $body = (string) $response->getBody();

        // Ici, vous devriez parser le HTML pour extraire les données d'emploi
        // Cela peut nécessiter une bibliothèque comme Symfony DomCrawler ou Simple HTML DOM
        // Pour simplifier, nous allons retourner le body en JSON, mais dans un cas réel, vous devriez extraire les données pertinentes
        return [
            'html' => $body,
        ];
    }
}
