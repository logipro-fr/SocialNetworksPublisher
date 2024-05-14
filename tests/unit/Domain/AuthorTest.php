<?php

namespace SocialNetworks\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\Author;
use SocialNetworks\Domain\Exceptions\BadAuthorIdException;
use SocialNetworks\Domain\Exceptions\BadPageIdException;
use SocialNetworks\Domain\Exceptions\BadSocialNetworksParameterException;

class AuthorTest extends TestCase
{
    public function testOrganizationAuthor(): void
    {
        $author = new Author('FaceBooK','248178251');
        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('248178251', $author->getId());
        $this->assertEquals('facebook', $author->getSocialNetwork());
    }

    public function testBadAuthorUrl(): void
    {
        $this->expectException(BadSocialNetworksParameterException::class);
        $this->expectExceptionMessage("The social network parameters cannot be empty");
        new Author("", "123156");
    }

    public function testBadAuthorId() : void {
        $this->expectException(BadAuthorIdException::class);
        $this->expectExceptionMessage("The id parameters cannot be empty");
        new Author("facebook", "");
    }
}
