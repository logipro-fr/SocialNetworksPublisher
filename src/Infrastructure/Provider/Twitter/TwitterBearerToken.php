<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use DateTime;
use Exception;
use Safe\DateTime as SafeDateTime;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\BadRequestException;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\file_get_contents;
use function Safe\file_put_contents;

class TwitterBearerToken
{
    public const BEARER_PATH = "/var/BearerToken.txt";
    public const REFRESH_PATH = "/var/RefreshToken.txt";
    public const EXPIRATION_PATH = "/var/TokenExpiration.txt";

    private string $bearerPath;
    private string $refreshPath;
    private string $expirationPath;

    public function __construct(
        private HttpClientInterface $client,
        string $bearerPath = self::BEARER_PATH,
        string $refreshPath = self::REFRESH_PATH,
        string $expirationPath = self::EXPIRATION_PATH,
    ) {
        $this->bearerPath = CurrentWorkDirPath::getPath() . $bearerPath;
        $this->refreshPath = CurrentWorkDirPath::getPath() . $refreshPath;
        $this->expirationPath = CurrentWorkDirPath::getPath() . $expirationPath;

        if (!file_exists($this->refreshPath)) {
            file_put_contents($this->refreshPath, $_ENV['TWITTER_REFRESH_TOKEN']);
            $this->refreshRequest();
        }

        $this->needsRefresh();
    }

    public function getBearerToken(): string
    {
        return file_get_contents($this->bearerPath);
    }

    public function getRefreshToken(): string
    {
        return file_get_contents($this->refreshPath);
    }

    public function getExpirationDate(): DateTime
    {
        /** @var DateTime */
        $date = date_create_from_format(DateTime::ATOM, file_get_contents($this->expirationPath));
        return $date;
    }

    public function needsRefresh(): void
    { 
        if (!file_exists($this->expirationPath)){
            $this->refreshRequest();
        }
        $expirationDate = $this->getExpirationDate();
        $currentDate = new DateTime();
        if ($currentDate >= $expirationDate->modify('+2 hours')) {
            $this->refreshRequest();
        }
    }

    private function refreshRequest(): void
    {
        $url = "https://api.twitter.com/2/oauth2/token";
        $data = json_encode([
            'refresh_token' => $this->getRefreshToken(),
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
        if ($response->getStatusCode() !== 200) {
            throw new BadRequestException(
                "Invalid request " . $response->getStatusCode(),
                BadRequestException::ERROR_CODE
            );
        }
        /** @var array<string,mixed> */
        $responseData = json_decode($response->getContent(), true);
        /** @var string */
        $accessToken = $responseData['access_token'];
        /** @var string */
        $refreshToken = $responseData['refresh_token'];
        file_put_contents($this->bearerPath, $accessToken);
        file_put_contents($this->refreshPath, $refreshToken);
        file_put_contents($this->expirationPath, (new SafeDateTime('+ 2 hours'))->format(DateTime::ATOM));
    }
}
