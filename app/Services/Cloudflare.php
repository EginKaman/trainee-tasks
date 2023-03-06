<?php

declare(strict_types=1);

namespace App\Services;

use Cloudflare\API\Adapter\Guzzle as Adapter;
use Cloudflare\API\Auth\APIKey as Key;
use Cloudflare\API\Endpoints\{AccessRules,
    Crypto,
    DNS,
    FirewallSettings,
    IPs,
    LoadBalancers,
    Membership,
    Pools,
    Railgun,
    SSL,
    TLS,
    UARules,
    User,
    WAF,
    ZoneLockdown,
    ZoneSettings,
    Zones
};
use Illuminate\Support\Traits\Macroable;

final class Cloudflare
{
    use Macroable;

    private Adapter $adapter;

    public function __construct(string $email, string $api)
    {
        $key = new Key($email, $api);
        $this->adapter = new Adapter($key);
    }

    public function dns(): DNS
    {
        return new DNS($this->adapter);
    }

    public function zone(): Zones
    {
        return new Zones($this->adapter);
    }

    public function zoneLockdown(): ZoneLockdown
    {
        return new ZoneLockdown($this->adapter);
    }

    public function zoneSetting(): ZoneSettings
    {
        return new ZoneSettings($this->adapter);
    }

    public function ip(): IPs
    {
        return new IPs($this->adapter);
    }

    public function ssl(): SSL
    {
        return new SSL($this->adapter);
    }

    public function tls(): TLS
    {
        return new TLS($this->adapter);
    }

    public function crypto(): Crypto
    {
        return new Crypto($this->adapter);
    }

    public function rule(): AccessRules
    {
        return new AccessRules($this->adapter);
    }

    public function firewall(): FirewallSettings
    {
        return new FirewallSettings($this->adapter);
    }

    public function loadBalance(): LoadBalancers
    {
        return new LoadBalancers($this->adapter);
    }

    public function membership(): Membership
    {
        return new Membership($this->adapter);
    }

    public function pool(): Pools
    {
        return new Pools($this->adapter);
    }

    public function railgun(): Railgun
    {
        return new Railgun($this->adapter);
    }

    public function userAgent(): UARules
    {
        return new UARules($this->adapter);
    }

    public function user(): User
    {
        return new User($this->adapter);
    }

    public function waf(): WAF
    {
        return new WAF($this->adapter);
    }
}
