<?php

namespace SocialNetworksPublisher\Domain;

enum Status: string
{
    case DRAFT = "draft";
    case READY = "ready";
}
