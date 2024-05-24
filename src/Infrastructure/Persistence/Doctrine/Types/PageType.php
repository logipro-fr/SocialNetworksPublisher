<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Post\Page;

class PageType extends Type
{
    public const TYPE_NAME = 'page';
    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'text';
    }
    /**
     * @param Page $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return serialize($value);
    }
    /**
     * @param String $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return unserialize($value);
    }
}
