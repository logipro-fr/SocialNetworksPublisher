<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Page\PageId;

class PageIdType extends Type
{
    public const TYPE_NAME = 'page_id';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param PageId $value
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
    public function convertToPHPValue($value, AbstractPlatform $platform): PageId
    {
        return new PageId($value);
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
