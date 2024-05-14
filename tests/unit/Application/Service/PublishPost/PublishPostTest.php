<?php

namespace SocialNetworksPublisher\Tests\Application\Service\PublishPost;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;

class PublishPostTest extends TestCase
{
    private const TEXT_CONTENT =
        "Following a prediction made at 10:00, an accident occurred on N02 at 10:35. 
        This underscores the importance of accident prediction in prevention.";
    public function testExecuteWithHashTag(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->once())
            ->method('postApiRequest')
            ->willReturn(new PublishPostResponse(201, "published"));

        $service = new PublishPost($mockInterface);
        $request = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            "#AccidentPrediction,#RoadSafety",
            "published"
        );

        $service->execute($request);
        $response = $service->getResponse();
        $this->assertInstanceOf(PublishPostResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals("published", $response->status);
    }

    public function testExecuteWithoutHashTag(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->once())
            ->method('postApiRequest')
            ->willReturn(new PublishPostResponse(201, "published"));

        $service = new PublishPost($mockInterface);
        $request = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            ""
        );

        $service->execute($request);
        $response = $service->getResponse();
        $this->assertInstanceOf(PublishPostResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals("published", $response->status);
    }

    public function testExecuteMultipleTime(): void
    {
        $mockInterface = $this->createMock(ApiInterface::class);
        $mockInterface
            ->expects($this->exactly(2))
            ->method('postApiRequest')
            ->willReturnOnConsecutiveCalls(
                new PublishPostResponse(201, "published"),
                new PublishPostResponse(201, "draft")
            );

        $request = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            "#PEdro",
            "published"
        );
        $service = new PublishPost($mockInterface);

        $service->execute($request);
        $response = $service->getResponse();
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals("published", $response->status);

        $request2 = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            "",
            "draft"
        );

        $service->execute($request2);
        $response = $service->getResponse();
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals("draft", $response->status);
    }
}
