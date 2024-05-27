<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AuthorType extends Type
{
    public const TYPE_NAME = "author";
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return serialize($value);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        var_dump($column);
        return $platform->getStringTypeDeclarationSQL($column);
    }
    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return unserialize($value);
    }
}
