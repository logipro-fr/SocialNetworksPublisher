<?php

namespace SocialNetworksPublisher\Infrastructure;

use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworksPublisher\Domain\Model\Post\Post;

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
    public function postApiRequest(Post $post): PublishPostResponse
    {
        fwrite($this->file, $post->getContent()->__toString() . " " . $post->getHashTags()->__toString() . "\n\n");
        return new PublishPostResponse(
            success: true,
            statusCode: 201,
            data: (object) ['postId' => (string) $post->getPostId()]
        );
    }
}
