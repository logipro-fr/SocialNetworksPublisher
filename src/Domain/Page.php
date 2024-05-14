<?php

namespace SocialNetworks\Domain;

use SocialNetworks\Domain\Exceptions\EmptyPageIdException;
use SocialNetworks\Domain\Exceptions\EmptyPageNameException;

class Page
{
    /** @var PostId[] */
    private array $postsIdList = [];
    public function __construct(private string $name, private string $id,)
    {
        if (empty($this->name)) {
            throw new EmptyPageNameException();
        }

        if (empty($this->id)) {
            throw new EmptyPageIdException();
        }
    }

    public function addPost(PostId $post): void
    {
        $this->postsIdList[] = $post;
    }

    /** @return PostId[] */
    public function getPostsId(): array
    {
        return $this->postsIdList;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
