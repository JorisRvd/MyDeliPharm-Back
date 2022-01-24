<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Classe d'accès à l'API d'omdbapi.com
 */
class magosmApi
{
    // Les services nécessaires
    // On utilise le composant HttpClient de Symfony
    // @link https://symfony.com/doc/current/http_client.html
    private $httpClient;
    // Pour récupérer les paramètres de services.yaml (mais pas que !) depuis notre code
    private $parameterBag;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
    }

    /**
     * Renvoie le contenu JSON du film demandé
     * 
     * @param string $title Movie title
     */
    public function fetch(string $name)
    {
        // On envoie une requête chez omdbapi.com
        $response = $this->httpClient->request(
            'GET',
            'https://www.data.gouv.fr/fr/datasets/r/0367b8c0-74ad-4891-a107-26d241b2f595',
            // @link https://symfony.com/doc/current/http_client.html#query-string-parameters
            [
                'query' => [
                    'n' => $name, // urlencode() sera appliqué dessus
                    'apiKey' => $this->parameterBag->get('app.magosm_api_key'),
                ]
            ]
        );

        // On convertit le JSON en tableau PHP
        $responseArray = $response->toArray();

        return $responseArray;
    }

    /**
     * Renvoie l'URL du poster d'un film donné
     * 
     * @param string $title Movie title
     * 
     * @return string Poster's URL
     */
    public function fetchName(string $name)
    {
        $content = $this->fetch($name);

        // Le poster est-il manquant ?
        if (!array_key_exists('name', $content)) {
            return null;
        }

        return $content['name'];
    }
}