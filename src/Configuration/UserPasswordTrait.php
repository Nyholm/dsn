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

    /**
     * @return array
     */
    public function getAuthentication()
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

    public function getPassword(): ?string
    {
        return $this->authentication['password'] ?? null;
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
