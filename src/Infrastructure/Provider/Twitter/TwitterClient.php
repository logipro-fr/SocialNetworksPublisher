<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\BadRequestException;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\DuplicatePostException;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\UnauthorizedException;
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
        if ($response->getStatusCode() === 200 ||$response->getStatusCode() === 201) {
            return new ProviderResponse(true);
        } elseif ($response->getStatusCode() === 401) {
            throw new UnauthorizedException("Unauthorized", UnauthorizedException::ERROR_CODE);
        } elseif ($response->getStatusCode() === 403) {
            throw new DuplicatePostException(
                "You are not allowed to create a Tweet with duplicate content",
                DuplicatePostException::ERROR_CODE,
            );
        } else {
            throw new BadRequestException("An error occured during the twitter proccess" . $response->getStatusCode(), 500);
        }
    }
}
