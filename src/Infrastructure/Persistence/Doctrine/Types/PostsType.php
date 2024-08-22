<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;

use function Safe\json_decode;
use function Safe\json_encode;

class PostsType extends Type
{
    public const TYPE_NAME = 'posts';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return Collection<int, Post>
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Collection
    {

        /** @var array<\stdClass> $objs */
        $objs = json_decode($value);
        $posts = new ArrayCollection();

        foreach ($objs as $post) {
            $createdAt = new DateTimeImmutable($post->createdAt->date);

            $posts->add(
                new Post(
                    $post->content,
                    PostStatus::tryFrom($post->status),
                    new PostId($post->id),
                    $createdAt,
                )
            );
        }
        return $posts;
    }

    /**
     * @param ArrayCollection<int, Post> $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $db = [];
        foreach ($value as $post) {
            $db[] = [
                "id" => $post->getPostId()->__toString(),
                "content" => $post->getContent(),
                "status" => $post->getStatus(),
                "createdAt" => $post->getCreatedAt(),
            ];
        }

        return json_encode($db);
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}
