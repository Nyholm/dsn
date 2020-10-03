<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Configuration;

trait UserPasswordTrait
{
    /**
     * @var array{
     *             user: string|null,
     *             password: string|null,
     *             }
     */
    private $authentication = ['user' => null, 'password' => null];

    public function getAuthentication(): array
    {
        return $this->authentication;
    }

    private function setAuthentication(array $authentication): void
    {
        if (!empty($authentication)) {
            $this->authentication = $authentication;
        }
    }

    public function getUser(): ?string
    {
        return $this->authentication['user'] ?? null;
    }

    public function withUser(?string $user): self
    {
        $new = clone $this;
        $new->authentication['user'] = $user;

        return $new;
    }

    public function getPassword(): ?string
    {
        return $this->authentication['password'] ?? null;
    }

    public function withPassword(?string $password): self
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
