<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog;

use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\Status;
use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Infrastructure\Provider\ProviderResponseInterface;

use function Safe\fclose;
use function Safe\fopen;
use function Safe\fwrite;

class SimpleBlog implements SocialNetworksApiInterface
{
    /** @var resource file pointer resource*/
    private $file;
    public function __construct(private string $filePath)
    {
        $this->verifyFilePath($this->filePath);
        $this->file = fopen($this->filePath, 'c+b');
    }
    public function postApiRequest(Post $post): ProviderResponse
    {
        fwrite($this->file, $post->getContent()->__toString() . " " . $post->getHashTags()->__toString() . "\n\n");
        return new ProviderResponse(
            true
        );
    }
    protected function verifyFilePath(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
