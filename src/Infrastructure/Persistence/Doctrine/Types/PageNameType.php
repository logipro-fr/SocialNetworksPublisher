<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Post\Content;

class PageNameType extends Type
{
    public const TYPE_NAME = 'page_name';
    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "text";
    }

    /**
     * @param PageName $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->__toString();
    }
    /**
     * @param string $value
     * @return PageName
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): PageName
    {
        /** @var PageName */
        return new PageName($value);
    }
}
