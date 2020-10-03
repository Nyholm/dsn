<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Configuration;

trait UserPasswordTrait
{
    /**
     * @var array{ user: string|null, password: string|null, }
     */
    private $authentication = ['user' => null, 'password' => null];

    public function getAuthentication(): array
    {
        return $this->authentication;
    }

    /**
     * @param array{ user?: string|null, password?: string|null, } $authentication
     */
    private function setAuthentication(array $authentication): void
    {
        if (!empty($authentication)) {
            $this->authentication['user'] = $authentication['user'] ?? null;
            $this->authentication['password'] = $authentication['password'] ?? null;
        }
    }

    public function getUser(): ?string
    {
        return $this->authentication['user'] ?? null;
    }

    /**
     * @return static
     */
    public function withUser(?string $user)
    {
        $new = clone $this;
        $new->authentication['user'] = $user;

        return $new;
    }

    public function getPassword(): ?string
    {
        return $this->authentication['password'] ?? null;
    }

    /**
     * @return static
     */
    public function withPassword(?string $password)
    {
        $new = clone $this;
        $new->authentication['password'] = $password;

        return $new;
    }

    private function getUserInfoString(): string
    {
        $user = $this->getUser() ?? '';
        $password = $this->getPassword() ?? '';

        if ('' === $password && '' === $user) {
            return '';
        }

        return $user.('' === $password ? '' : ':'.$password).'@';
    }
}
