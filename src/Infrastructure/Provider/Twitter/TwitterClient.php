<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\BadRequestException;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\UnauthorizedException;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_encode;

class TwitterClient implements SocialNetworksApiInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private TwitterBearerToken $bearerToken
    ) {
    }

    public function postApiRequest(Post $post): ProviderResponse
    {
        $this->bearerToken->needsRefresh();
        $url = 'https://api.twitter.com/2/tweets';
        $data = [
            'text' => $post->getContent()->__toString() . " " . $post->getHashTags()->__toString(),
        ];
        $data_json = json_encode($data);
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken->getBearerToken(),
                'Content-Type' => 'application/json',
            ],
            'body' => $data_json,
        ];
        $response = $this->client->request('POST', $url, $options);
        if ($response->getStatusCode() === 200) {
            return new ProviderResponse(true);
        } elseif ($response->getStatusCode() === 401) {
            throw new UnauthorizedException("Unauthorized", UnauthorizedException::ERROR_CODE);
        } else {
            return new ProviderResponse(false);
        }
    }
}
