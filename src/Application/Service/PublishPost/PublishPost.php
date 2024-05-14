<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Domain\Author;
use SocialNetworksPublisher\Domain\Content;
use SocialNetworksPublisher\Domain\HashTag;
use SocialNetworksPublisher\Domain\Page;
use SocialNetworksPublisher\Domain\Post;
use SocialNetworksPublisher\Domain\Status;

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
        $author = new Author($request->socialNetworks, $request->authorId);
        $content = new Content($request->content);
        $hashtag = new HashTag($request->hashtag);
        //Suspect
        $page = new Page($request->socialNetworks, $request->pageId);
        return new Post($author, $content, $hashtag, $page, Status::READY);
    }

    public function getResponse(): PublishPostResponse
    {
        return $this->response;
    }
}
