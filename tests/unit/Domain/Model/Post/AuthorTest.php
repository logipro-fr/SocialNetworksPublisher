<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadAuthorIdException;

class AuthorTest extends TestCase
{
    public function testOrganizationAuthor(): void
    {
        $author = new Author('248178251');
        $author2 = new Author('248f17825a1');

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('248178251', $author->getId());
        $this->assertEquals('248f17825a1', $author2->getId());
    }
    public function testBadAuthorId(): void
    {
        $this->expectException(BadAuthorIdException::class);
        $this->expectExceptionMessage("The id parameters cannot be empty");
        $this->expectExceptionCode(BadAuthorIdException::ERROR_CODE);
        new Author("");
    }
}
