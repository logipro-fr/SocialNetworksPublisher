<?php

namespace SocialNetworksPublisher\Tests\Application\Service\PublishPost;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\Status;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\ProviderResponse;

class PublishPostTest extends TestCase
{
    private PublishPostRequest $requestHashTag;
    private PublishPostRequest $requestWithoutHashTag;
    private PublishPostRequest $request2;
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
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            ""
        );

        $this->request2 = new PublishPostRequest(
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
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->once())
            ->method('postApiRequest')
            ->willReturn(new ProviderResponse(new PostId("test"), "simpleBlog"));
        $service = new PublishPost($mockInterface, $this->repository, "test");

        $service->execute($this->requestHashTag);
        $response = $service->getResponse();
        $post = $this->repository->findById(new PostId($response->postId));

        $this->assertInstanceOf(PublishPostResponse::class, $response);
        $this->assertEquals("simpleBlog", $response->socialNetworks);
        $this->assertEquals("test", $response->postId);
        $this->assertEquals("test", $this->repository->findById(new PostId("test"))->getPostId());
        $this->assertEquals(Status::PUBLISHED, $post->getStatus());
    }

    public function testExecuteWithoutHashTag(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->once())
            ->method('postApiRequest')
            ->willReturn(new ProviderResponse(new PostId("test"), "simpleBlog"));
        $service = new PublishPost($mockInterface, $this->repository);

        $service->execute($this->requestWithoutHashTag);
        $response = $service->getResponse();

        $this->assertInstanceOf(PublishPostResponse::class, $response);
        $this->assertEquals("simpleBlog", $response->socialNetworks);
        $this->assertEquals("test", $response->postId);
    }

    public function testExecuteMultipleTime(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->exactly(2))
            ->method('postApiRequest')
            ->willReturnOnConsecutiveCalls(
                new ProviderResponse(new PostId("test1"), "simpleBlog"),
                new ProviderResponse(new PostId("test2"), "simpleBlog"),
            );
        $service = new PublishPost($mockInterface, $this->repository);

        $service->execute($this->requestHashTag);
        $response = $service->getResponse();
        $service->execute($this->request2);
        $responseOther = $service->getResponse();

        $this->assertEquals("simpleBlog", $response->socialNetworks);
        $this->assertEquals("test1", $response->postId);

        $this->assertEquals("simpleBlog", $responseOther->socialNetworks);
        $this->assertEquals("test2", $responseOther->postId);
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
