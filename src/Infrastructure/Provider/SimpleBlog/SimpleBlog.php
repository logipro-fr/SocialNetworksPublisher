<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog;

use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Provider\ProviderResponse;
use SocialNetworksPublisher\Infrastructure\Provider\ProviderResponseInterface;

use function Safe\fopen;
use function Safe\fwrite;

class SimpleBlog implements ApiInterface
{
    /** @var resource file pointer resource*/
    private $file;
    public function __construct(private string $filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $this->file = fopen($this->filePath, 'c+b');
    }
    public function postApiRequest(Post $post): ProviderResponse
    {
        fwrite($this->file, $post->getContent()->__toString() . " " . $post->getHashTags()->__toString() . "\n\n");
        return new ProviderResponse(
            $post->getPostId()->__toString(),
            "simpleBlog",
        );
    }
}
