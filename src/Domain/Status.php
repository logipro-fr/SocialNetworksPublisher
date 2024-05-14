<?php

namespace SocialNetworks\Domain;

enum Status: string
{
    case DRAFT = "draft";
    case READY = "ready";
}
