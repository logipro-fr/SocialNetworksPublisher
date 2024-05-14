<?php

namespace SocialNetworks\Domain;

use SocialNetworks\Domain\Exceptions\EmptyTargetStatusException;

class TargetStatus
{
    public function __construct(private Status $targetStatus)
    {
    }

    public function getTargetStatus(): string
    {
        return $this->targetStatus->value;
    }
}
