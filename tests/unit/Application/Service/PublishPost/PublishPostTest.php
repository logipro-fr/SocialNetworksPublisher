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
use SocialNetworksPublisher\Infrastructure\Persistence\PostRepositoryInMemory;

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
            ->willReturn(new PublishPostResponse(true, 201, new PostId()));
        $service = new PublishPost($mockInterface, $this->repository, "test");

        $service->execute($this->requestHashTag);
        $response = $service->getResponse();

        $this->assertInstanceOf(PublishPostResponse::class, $response);
        $this->assertTrue($response->success);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals("", $response->message);
        $this->assertNotEmpty($response->data);
        $this->assertEquals("test", $this->repository->findById(new PostId("test"))->getPostId());
    }

    public function testExecuteWithoutHashTag(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->once())
            ->method('postApiRequest')
            ->willReturn(new PublishPostResponse(true, 201, new PostId()));
        $service = new PublishPost($mockInterface, $this->repository);

        $service->execute($this->requestWithoutHashTag);
        $response = $service->getResponse();

        $this->assertTrue($response->success);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals("", $response->message);
        $this->assertNotEmpty($response->data);
    }

    public function testExecuteMultipleTime(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->exactly(2))
            ->method('postApiRequest')
            ->willReturnOnConsecutiveCalls(
                new PublishPostResponse(true, 201, new PostId()),
                new PublishPostResponse(true, 201, new PostId()),
            );
        $service = new PublishPost($mockInterface, $this->repository);

        $service->execute($this->requestHashTag);
        $response = $service->getResponse();
        $service->execute($this->request2);
        $responseOther = $service->getResponse();

        $this->assertTrue($response->success);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals("", $response->message);
        $this->assertNotEmpty($response->data);

        $this->assertTrue($responseOther->success);
        $this->assertEquals(201, $responseOther->statusCode);
        $this->assertNotEmpty($responseOther->data);
        $this->assertEquals("", $responseOther->message);
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
