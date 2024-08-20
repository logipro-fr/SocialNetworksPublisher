<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Post\PostId;

class PostIdsType extends Type {
    const TYPE_NAME = 'post_ids'; 

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'TEXT';
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return array<PostId>
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $postIdsArray = explode(',', $value);
        return array_map(fn($id) => new PostId($id), $postIdsArray);
    }

    /** 
     * @param array<PostId> $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $postIds = array_map(fn(PostId $postId) => $postId->__toString(), $value);
        return implode(',', $postIds);
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}
