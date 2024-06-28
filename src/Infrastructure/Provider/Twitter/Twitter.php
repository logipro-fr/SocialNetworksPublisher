<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_encode;

class Twitter implements SocialNetworksApiInterface
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client = null, string $apiKey = "")
    {
        $this->client = $client ?: HttpClient::create();
        if (empty($apiKey)) {
            $this->apiKey = $_ENV['TWITTER_API_KEY'];
        }
    }
        
    public function postApiRequest(Post $post): ProviderResponse
    {
        $response = $this->client->request(
            'POST',
            'https://api.twitter.com/2/tweets',
            [
                'headers' => [
                    'content-type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'body' => json_encode([
                    'text' => $post->getContent(),
                ]),
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            return new ProviderResponse(true);
        } else {
            return new ProviderResponse(false);
        }
    }
}
