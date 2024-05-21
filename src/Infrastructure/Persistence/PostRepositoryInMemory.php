<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence;

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
        return $this->posts[$searchId->__toString()];
    }
}
