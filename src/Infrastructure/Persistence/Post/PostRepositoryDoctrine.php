<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Post;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\PostNotFoundException;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;

/**
 * @extends EntityRepository<Post>
 */
class PostRepositoryDoctrine extends EntityRepository implements PostRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        $class = $em->getClassMetadata(Post::class);
        parent::__construct($em, $class);
    }
    public function add(Post $post): void
    {
        $this->getEntityManager()->persist($post);
    }

    public function findById(PostId $searchId): Post
    {
        $post = $this->getEntityManager()->find(Post::class, $searchId);
        if ($post === null) {
            throw new PostNotFoundException(
                sprintf("Error can't find the postId %s", $searchId),
                PostNotFoundException::ERROR_CODE
            );
        }
        return $post;
    }
}
