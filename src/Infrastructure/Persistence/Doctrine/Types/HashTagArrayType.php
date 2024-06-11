<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;

use function Safe\json_decode;
use function Safe\json_encode;

class HashTagArrayType extends Type
{
    public const TYPE_NAME = "hash_tag_array";
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'json';
    }
    /** @param HashTagArray $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return json_encode($value->toArray());
        //return serialize($value);

        //return json_encode($value->getHashTags());
    }
    /** @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): HashTagArray
    {
        /** @var array<string> */
        $data = json_decode($value);
        return HashTagArray::fromArray($data);
    }
}
