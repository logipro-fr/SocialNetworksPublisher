<?php
namespace SocialNetworksPublisher\Domain\Model\Post;

enum SocialNetworks: string {
    case Facebook = "Facebook";
    case LinkedIn= "LinkedIn";
    case SimpleBlog = "SimpleBlog";
}