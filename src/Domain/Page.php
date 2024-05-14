<?php

namespace SocialNetworks\Domain;

use SocialNetworks\Domain\Exceptions\EmptyPageIdException;
use SocialNetworks\Domain\Exceptions\EmptyPageNameException;

use function PHPUnit\Framework\isNull;

class Page
{
    /** @var Post[] */
    private array $postsList = [];
    private PageId $id;
    public function __construct(private string $name, ?PageId $id = null,)
    {
        if ($id == null) {
            $this->id = new PageId();
        } else {
            $this->id = $id;
        }

        if (empty($this->name)) {
            throw new EmptyPageNameException();
        }

    }

    public function addPost(Post $post): void
    {
        $this->postsList[] = $post;
    }

    /** @return Post[] */
    public function getPosts(): array
    {
        return $this->postsList;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): PageId {
        return $this->id;
    }

}
