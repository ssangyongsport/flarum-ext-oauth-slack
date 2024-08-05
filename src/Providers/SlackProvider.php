<?php

/*
 * This file is part of blomstra/oauth-logto.
 *
 * Copyright (c) 2022 Team Blomstra.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blomstra\OAuthLogto\Providers;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class LogtoProvider extends AbstractProvider
{
    protected $endpoint;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->endpoint = $options['endpoint'] ?? 'https://auth.ssangyongsports.eu.org';
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->endpoint . '/oidc/auth';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->endpoint . '/oidc/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->endpoint . '/oidc/me';
    }

    protected function getDefaultScopes()
    {
        return ['openid', 'profile', 'email', 'phone', 'custom_data', 'identities'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                $data['error_description'] ?? $data['error'] ?? 'Unknown error',
                $response->getStatusCode(),
                $data
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new LogtoResourceOwner($response);
    }

    protected function prepareAccessTokenResponse(array $result)
    {
        $result = parent::prepareAccessTokenResponse($result);

        return [
            'access_token'      => $result['access_token'],
            'resource_owner_id' => $result['id_token'],
        ];
    }

    protected function getAuthorizationHeaders($token = null)
    {
        return ['Authorization' => 'Bearer ' . $token];
    }
}
