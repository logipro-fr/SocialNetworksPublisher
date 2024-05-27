<?
namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class HashTagArrayType extends Type {
    public const TYPE_NAME = "hashTagArray";
    public function getName(): string {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        
    }
}