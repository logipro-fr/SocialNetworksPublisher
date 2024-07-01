<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_encode;

class Twitter implements SocialNetworksApiInterface
{

    public function __construct(private HttpClientInterface $client)
    {

    }
        
    public function postApiRequest(Post $post): ProviderResponse
    {
        $url = 'https://api.twitter.com/2/tweets';
        $data = [
            'text' => $post->getContent()->__toString(),
        ];
        $data_json = json_encode($data);
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . 'M2hmZ0VMdC1zb0Y5N1RSYk1MYkh4X0hWd25WMFpxeWNjbTFWeFpISEZ4OUp3OjE3MTk4NDIxMDkxNjE6MTowOmF0OjE',
                'Content-Type' => 'application/json',
            ],
            'body' => $data_json,
        ];
        $response = $this->client->request('POST', $url, $options);

        if ($response->getStatusCode() === 201) {
            return new ProviderResponse(true);
        } else {
            return new ProviderResponse(false);
        }

        // // Construire l'URL de l'API
        // $url = 'https://api.twitter.com/2/tweets';

        // // Préparer les données de la requête
        // $data = array(
        //     'text' => $post->getContent()->__toString(),
        // );
        // $data_json = json_encode($data);

        // // Initialiser cURL
        // $ch = curl_init();

        // // Configurer les options de cURL
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     'Authorization: Bearer ' . "M2hmZ0VMdC1zb0Y5N1RSYk1MYkh4X0hWd25WMFpxeWNjbTFWeFpISEZ4OUp3OjE3MTk4NDIxMDkxNjE6MTowOmF0OjE",
        //     'Content-Type: application/json'
        // ));
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);

        // // Exécuter la requête et obtenir la réponse
        // $response = curl_exec($ch);

        // // Vérifier s'il y a eu une erreur
        // if(curl_errno($ch)) {
        //     echo 'Erreur cURL: ' . curl_error($ch);
        //     // Fermer cURL
        //     curl_close($ch);
        //     return new ProviderResponse(false);    
        // } else {
        //     // Afficher la réponse de Twitter
        //     echo 'Réponse de Twitter: ' . $response;
        //     return new ProviderResponse(true);
        // }


    }

}

