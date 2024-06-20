<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

interface PostRepositoryInterface
{
    public function add(Post $post): void;
    public function findById(PostId $searchId): Post;
}
