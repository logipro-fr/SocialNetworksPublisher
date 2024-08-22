<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

enum PostStatus: string
{
    case DRAFT = "draft";
    case READY = "ready";
    case PUBLISHED = "published";
}
