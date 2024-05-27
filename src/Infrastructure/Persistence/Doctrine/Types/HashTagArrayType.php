<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Behat\Behat\Tester\Exception\PendingException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;

class HashTagArrayType extends Type
{
    public const TYPE_NAME = "hashTagArray";
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'text';
    }
    /** @param HashTagArray $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return serialize($value);
    }
    /** @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): HashTagArray
    {
        /** @var HashTagArray */
        return unserialize($value);
    }
}
