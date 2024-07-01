<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog;

use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;

use function Safe\fopen;
use function Safe\fwrite;

class SimpleBlog implements SocialNetworksApiInterface
{
    /** @var resource file pointer resource*/
    private $file;
    private const POST_PATTERN = "%s %s\n\n";
    public function __construct(private string $filePath)
    {
        $this->verifyFilePath($this->filePath);
        $this->file = fopen($this->filePath, 'c+b');
    }
    public function postApiRequest(Post $post): ProviderResponse
    {
        fwrite($this->file, sprintf(
            self::POST_PATTERN,
            $post->getContent()->__toString(),
            $post->getHashTags()->__toString()
        ));
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
