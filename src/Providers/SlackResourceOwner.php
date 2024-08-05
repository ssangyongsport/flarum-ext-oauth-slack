<?php

/*
 * This file is part of blomstra/oauth-logto.
 *
 * Copyright (c) 2022 Team Blomstra.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blomstra\OAuthSlack\Providers;

use Illuminate\Support\Arr;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class LogtoResourceOwner implements ResourceOwnerInterface
{
    protected $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function toArray()
    {
        return $this->response;
    }

    public function getId(): ?string
    {
        return Arr::get($this->response, 'sub'); // 'sub' is the standard claim for the subject identifier
    }

    public function getName(): ?string
    {
        return Arr::get($this->response, 'name');
    }

    public function getFirstName(): ?string
    {
        return Arr::get($this->response, 'given_name');
    }

    public function getLastName(): ?string
    {
        return Arr::get($this->response, 'family_name');
    }

    public function getEmail(): ?string
    {
        return Arr::get($this->response, 'email');
    }

    public function getPicture(): ?string
    {
        return Arr::get($this->response, 'picture'); // 'picture' is the standard claim for profile image
    }
}
