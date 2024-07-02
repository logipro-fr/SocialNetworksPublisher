<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_encode;

class TwitterClient implements SocialNetworksApiInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private TwitterBearerToken $bearerToken
    ) {
        $refreshPath = CurrentWorkDirPath::getPath() . $bearerToken::REFRESH_PATH;
        if (!file_exists($refreshPath)) {
            $this->bearerToken->setRefreshToken($_ENV['TWITTER_REFRESH_TOKEN']);
            $this->refreshToken();
        }
    }

    public function postApiRequest(Post $post): ProviderResponse
    {
        if ($this->bearerToken->needsRefresh()) {
            $this->refreshToken();
        }

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
        } else {
            return new ProviderResponse(false);
        }
    }
    /**
     * @return array<string,mixed>
     */
    private function refreshToken(): array
    {
        $url = "https://api.twitter.com/2/oauth2/token";
        $data = json_encode([
            'refresh_token' => $this->bearerToken->getRefreshToken(),
            'grant_type' => "refresh_token",
            'client_id' => $_ENV['TWITTER_CLIENT_ID'],
        ]);
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $data,
        ];
        $response = $this->client->request('POST', $url, $options);
        /** @var array<string,mixed> */
        $responseData = json_decode($response->getContent(), true);
        /** @var string */
        $accessToken = $responseData['access_token'];
        /** @var string */
        $refreshToken = $responseData['refresh_token'];
        $this->bearerToken->setBearerToken($accessToken, new \DateTime());
        $this->bearerToken->setRefreshToken($refreshToken);
        return $responseData;
    }
}
