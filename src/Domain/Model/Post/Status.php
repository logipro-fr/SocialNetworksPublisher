<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

enum Status: string
{
    case DRAFT = "draft";
    case READY = "ready";
    case PUBLISHED = "published";
}
