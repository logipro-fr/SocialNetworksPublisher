<?php

namespace SocialNetworks\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\Author;
use SocialNetworks\Domain\Exceptions\BadAuthorIdException;
use SocialNetworks\Domain\Exceptions\BadAuthorNameException;
use SocialNetworks\Domain\Exceptions\BadAuthorTypeException;

class AuthorTest extends TestCase
{
    public function testOrganizationAuthor(): void
    {
        $author = new Author(Author::ORGANIZATION, '123456', 'Logipro');

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('urn:li:organization:123456', $author->getUrn());
        $this->assertEquals('Logipro', $author->getName());
    }

    public function testPersonAuthor(): void
    {
        $author = new Author(Author::PERSON, '123456', 'Pedro');

        $this->assertEquals('urn:li:person:123456', $author->getUrn());
        $this->assertEquals('Pedro', $author->getName());
    }

    public function testNotValidAuthorType(): void
    {
        $this->expectException(BadAuthorTypeException::class);
        $this->expectExceptionMessage("The author type cannot be null");
        new Author("", "123456", "Pedro");
    }

    public function testNoValidAuthorId(): void
    {
        $this->expectException(BadAuthorIdException::class);
        $this->expectExceptionMessage("The author id cannot be null");
        new Author(Author::ORGANIZATION, "", "Pedro");
    }

    public function testNoValidAuthorName(): void
    {
        $this->expectException(BadAuthorNameException::class);
        $this->expectExceptionMessage("The author name cannot be null");
        new Author(Author::ORGANIZATION, "123456", "");
    }
}
