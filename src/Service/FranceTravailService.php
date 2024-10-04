<?php

namespace App\Service;

use GuzzleHttp\Client;

class FranceTravailService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function searchJobs(string $jobTitle, string $location): array
    {
        $url = 'https://candidat.francetravail.fr/offres/recherche?';
        $query = [
            'lieux' => $location,
            'motsCles' => $jobTitle,
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

        return [
            'html' => $body,
        ];
    }
}
