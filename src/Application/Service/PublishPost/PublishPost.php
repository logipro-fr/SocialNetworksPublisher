<?php

namespace SocialNetworks\Application\Service\PublishPost;

use SocialNetworks\Application\Service\ApiInterface;
use SocialNetworks\Domain\Author;
use SocialNetworks\Domain\Content;
use SocialNetworks\Domain\HashTag;
use SocialNetworks\Domain\Page;
use SocialNetworks\Domain\Post;
use SocialNetworks\Domain\Status;
use SocialNetworks\Domain\TargetStatus;

use function PHPUnit\Framework\isNull;

class PublishPost
{
    private PublishPostResponse $response;
    public function __construct(private ApiInterface $api)
    {
    }
    public function execute(PublishPostRequest $request): void
    {
        $post = $this->createPost($request);
        //return $this->api->postApiRequest($post);

        $apiResponse = $this->api->postApiRequest($post);
        $this->response = new PublishPostResponse($apiResponse->statusCode, $apiResponse->status);
    }

    private function createPost(PublishPostRequest $request): Post
    {
        $author = new Author($request->authorType, $request->authorId, $request->authorName);
        $content = new Content($request->content);
        $hashtag = new HashTag($request->hashtag);
        $page = new Page($request->pageName, $request->pageId);
        $targetStatus = new TargetStatus(Status::from($request->targetStatus));
        return new Post($author, $content, $hashtag, $page, $targetStatus);
    }

    public function getResponse(): PublishPostResponse
    {
        return $this->response;
    }
}
