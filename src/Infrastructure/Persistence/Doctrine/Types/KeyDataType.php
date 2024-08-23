<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Infection\Mutator\Boolean\InstanceOf_;
use OndraM\CiDetector\Ci\AbstractCi;
use SocialNetworksPublisher\Domain\Model\Key\AbstractKeyData;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\SocialNetworksDoesntExist;

use function Safe\json_decode;
use function Safe\json_encode;

class KeyDataType extends Type
{
    public const TYPE_NAME = 'key_data';
    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "text";
    }

    /**
     * @param AbstractKeyData $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof TwitterKeyData) {
            return json_encode([
                "socialNetwork" => "Twitter",
                "bearerToken" => $value->getBearerToken(),
                "refreshToken" => $value->getRefreshToken()
            ]);
        } else {
            throw new SocialNetworksDoesntExist("");
        }
    }
    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): AbstractKeyData
    {
        /** @var \stdClass> $obj */
        $obj = json_decode($value);


        if ($obj->socialNetwork == "Twitter") {
            return new TwitterKeyData(
                $obj->bearerToken,
                $obj->refreshToken,
            );
        } else {
            throw new SocialNetworksDoesntExist("");
        }
    }
}
