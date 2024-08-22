<?php

namespace SocialNetworksPublisher\Application\Service\Page\AddPost;

use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;

class AddPost
{
    private AddPostResponse $response;
    public function __construct(
        private PageRepositoryInterface $pages
    ) {
    }
    public function execute(AddPostRequest $request): void
    {
        $post = new Post(
            $request->postContent,
            PostStatus::READY
        );

        $page = $this->pages->findById(new PageId($request->pageId));
        $page->addPost(
            $post
        );
        $this->response = new AddPostResponse(
            $post->getPostId()
        );
    }

    public function getResponse(): AddPostResponse
    {
        return $this->response;
    }
}
