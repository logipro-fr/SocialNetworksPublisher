<?php

namespace SocialNetworksPublisher\Tests\Application\Service\PublishPost;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\SocialNetworksApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\Status;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryDoctrine;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\FactorySocialNetworksApi;

class PublishPostTest extends TestCase
{
    private PublishPostRequest $requestHashTag;
    private PublishPostRequest $requestWithoutHashTag;
    private PublishPostRequest $otherRequest;
    private PostRepositoryInMemory $repository;

    private const TEXT_CONTENT =
        "Following a prediction made at 10:00, an accident occurred on N02 at 10:35. 
        This underscores the importance of accident prediction in prevention.";

    public function setUp(): void
    {
        $this->requestHashTag = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            "#PEdro",
        );

        $this->requestWithoutHashTag = new PublishPostRequest(
            "simpleblog",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            ""
        );

        $this->otherRequest = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            ""
        );
        $this->repository = new PostRepositoryInMemory();
    }
    public function testExecuteWithHashTag(): void
    {
        $service = new PublishPost(new FactorySocialNetworksApi(), $this->repository);

        $service->execute($this->requestHashTag);
        $response = $service->getResponse();
        $postFromRepo = $this->repository->findById(new PostId($response->postId));

        $this->assertInstanceOf(PublishPostResponse::class, $response);
        $this->assertEquals("facebook", $response->socialNetworks);
        $this->assertStringStartsWith("pos_", $response->postId);
        $this->assertEquals(Status::PUBLISHED, $postFromRepo->getStatus());
    }

    public function testExecuteWithoutHashTag(): void
    {
        $service = new PublishPost(new FactorySocialNetworksApi(), $this->repository);

        $service->execute($this->requestWithoutHashTag);
        $response = $service->getResponse();

        $this->assertInstanceOf(PublishPostResponse::class, $response);
        $this->assertEquals("simpleblog", $response->socialNetworks);
        $this->assertStringStartsWith("pos_", $response->postId);
    }

    public function testExecuteMultipleTime(): void
    {
        $service = new PublishPost(new FactorySocialNetworksApi(), $this->repository);

        $service->execute($this->requestWithoutHashTag);
        $response = $service->getResponse();
        $service->execute($this->otherRequest);
        $responseOther = $service->getResponse();

        $this->assertEquals("simpleblog", $response->socialNetworks);
        $this->assertEquals("facebook", $responseOther->socialNetworks);
        $this->assertFalse((new PostId($responseOther->postId))->equals(new PostId($response->postId)));
    }

    public function testPostFactory(): void
    {
        $postFactory = new PostFactory();

        $post = $postFactory->buildPostFromRequest($this->requestHashTag);

        $this->assertInstanceOf(Post::class, $post);
    }

    public function testPostFactoryWithCustomPostId(): void
    {
        $postFactory = new PostFactory();

        $post = $postFactory->buildPostFromRequest($this->requestHashTag, "test");

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals("test", $post->getPostId());
    }
}
