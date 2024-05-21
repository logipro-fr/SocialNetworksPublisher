<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadAuthorIdException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;

class AuthorTest extends TestCase
{
    public function testOrganizationAuthor(): void
    {
        $author = new Author('FaceBooK', '248178251');
        $author2 = new Author('LinkedIn', '248178251');

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('248178251', $author->getId());
        $this->assertEquals('facebook', $author->getSocialNetwork());
        $this->assertEquals('linkedin', $author2->getSocialNetwork());
    }

    public function testBadAuthorUrl(): void
    {
        $this->expectException(BadSocialNetworksParameterException::class);
        $this->expectExceptionMessage("The social network parameters cannot be empty");
        $this->expectExceptionCode(BadSocialNetworksParameterException::ERROR_CODE);
        new Author("", "123156");
    }

    public function testBadAuthorId(): void
    {
        $this->expectException(BadAuthorIdException::class);
        $this->expectExceptionMessage("The id parameters cannot be empty");
        $this->expectExceptionCode(BadAuthorIdException::ERROR_CODE);
        new Author("facebook", "");
    }
}
