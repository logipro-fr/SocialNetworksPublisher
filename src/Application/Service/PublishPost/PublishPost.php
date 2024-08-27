<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;

class PublishPost
{
    private PublishPostResponse $response;
    public function __construct(
        private PageRepositoryInterface $pages,
        private AbstractFactorySocialNetworksApi $socialNetworksFactory,
    ) {
    }
    public function execute(): void
    {
        $pageArray = $this->pages->findAll();
        $postIdResponse = [];
        foreach ($pageArray as $page) {
            $api = $this->socialNetworksFactory->buildApi($page->getSocialNetwork());
            $posts = $page->getPosts();

            foreach($posts as $post) {
                $success = $api->postApiRequest($post);
                if ($success) {
                    $post->setStatus(PostStatus::PUBLISHED);
                    $postIdResponse[] = $post->getPostId()->__toString();
                }
            }
        }
        $this->response = new PublishPostResponse(
            $postIdResponse
        );
    }

    public function getResponse(): PublishPostResponse
    {
        return $this->response;
    }
}
