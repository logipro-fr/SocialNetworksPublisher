<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Content;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArrayFactory;
use SocialNetworksPublisher\Domain\Model\Post\Page;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\SocialNetworks;
use SocialNetworksPublisher\Domain\Model\Post\Status;

class PostFactory
{
    public function buildPostFromRequest(PublishPostRequest $request, string $postIdName = ""): Post
    {
        return new Post(
            new Author($request->authorId),
            new Content($request->content),
            (new HashTagArrayFactory())->buildHashTagArrayFromSentence($request->hashtag, ", "),
            new Page($request->pageId),
            Status::READY,
            SocialNetworks::fromString($request->socialNetworks),
            new PostId($postIdName),
        );
    }
}
