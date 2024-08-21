<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Content;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArrayFactory;
use SocialNetworksPublisher\Domain\Model\Post\Page;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use PostStatus;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class PostFactory
{
    public function buildPostFromRequest(PublishPostRequest $request, string $postIdName = ""): Post
    {
        return new Post(
            new Author($request->authorId),
            new Content($request->content),
            (new HashTagArrayFactory())->buildHashTagArrayFromSentence($request->hashtag, ", "),
            new Page($request->pageId),
            PostStatus::READY,
            SocialNetworks::fromString($request->socialNetworks),
            new PostId($postIdName),
        );
    }
}
