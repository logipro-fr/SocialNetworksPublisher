<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PostId;

class PostIdType extends Type
{
    public const TYPE_NAME = 'post_id';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param PostId $value
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->__toString();
    }

    /**
     * @param string $value
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): PostId
    {
        return new PostId($value);
    }

    /**
     * @param mixed[] $column
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }
}
