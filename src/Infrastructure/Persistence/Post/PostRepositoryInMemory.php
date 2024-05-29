<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\PostNotFoundException;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;

class PostRepositoryInMemory implements PostRepositoryInterface
{
    /** @var Post[] */
    private array $posts;
    public function add(Post $post): void
    {
        $this->posts[$post->getPostId()->__toString()] = $post;
    }

    public function findById(PostId $searchId): Post
    {
        $postIdString = $searchId->__toString();
        if (!isset($this->posts[$postIdString])) {
            throw new PostNotFoundException(sprintf("Error can't find the postId %s",$postIdString), 400);
        }
        return $this->posts[$postIdString];
    }
}
