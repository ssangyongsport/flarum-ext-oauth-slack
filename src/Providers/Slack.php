<?php

namespace Blomstra\OAuthLogto\Providers;

use Flarum\Forum\Auth\Registration;
use FoF\OAuth\Provider;
use Logto\Sdk\LogtoClient;
use Logto\Sdk\LogtoConfig;

class Logto extends Provider
{
    /**
     * @var LogtoClient
     */
    protected $client;

    public function name(): string
    {
        return 'logto';
    }

    public function link(): string
    {
        return 'https://docs.logto.io/docs/intro';
    }

    public function fields(): array
    {
        return [
            'endpoint'     => 'required',
            'app_id'       => 'required',
            'app_secret'   => 'required',
        ];
    }

    public function provider(string $redirectUri): LogtoClient
    {
        return $this->client = new LogtoClient(
            new LogtoConfig(
                endpoint: $this->getSetting('endpoint'),
                appId: $this->getSetting('app_id'),
                appSecret: $this->getSetting('app_secret'),
                scopes: ['openid', 'profile', 'email', 'phone', 'custom_data', 'identities']
            )
        );
    }

    public function options(): array
    {
        return [];
    }

    public function suggestions(Registration $registration, $user, string $token)
    {
        $userInfo = $this->client->fetchUserInfo();

        $this->verifyEmail($email = $userInfo->email);

        $registration
            ->provideTrustedEmail($email)
            ->provideAvatar($userInfo->picture)
            ->suggestUsername($userInfo->name)
            ->setPayload($userInfo->toArray());
    }
}
