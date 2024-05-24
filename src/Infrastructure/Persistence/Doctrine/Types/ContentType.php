<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Post\Content;

class ContentType extends Type
{
    public const TYPE_NAME = 'content';
    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return "text";
    }

    /**
     * @param string $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return serialize($value);
    }
    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        /** @var Content */
        return unserialize($value);
    }
}
