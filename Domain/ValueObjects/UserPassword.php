<?php

declare(strict_types=1);

require_once __DIR__ . '/../Exceptions/InvalidUserPasswordException.php';

class UserPassword
{
    private string $value;

    public function __construct(string $value)
    {
        $normalized = trim($value);

        if ($normalized === '') {
            throw InvalidUserPasswordException::becauseValueIsEmpty();
        }

        if (strlen($normalized) < 8) {
            throw InvalidUserPasswordException::becauseLenghtIsTooShort($normalized);
        }

        $this->value = $normalized;
    }

    /**
     * Crea un UserPassword hasheando el texto plano con bcrypt.
     */
    public static function fromPlainText(string $plain): self
    {
        $normalized = trim($plain);

        if ($normalized === '') {
            throw InvalidUserPasswordException::becauseValueIsEmpty();
        }

        if (strlen($normalized) < 8) {
            throw InvalidUserPasswordException::becauseLenghtIsTooShort($normalized);
        }

        $hashed = password_hash($normalized, PASSWORD_BCRYPT);
        $instance = new self($hashed);
        return $instance;
    }

    /**
     * Crea un UserPassword desde un hash ya almacenado en BD (sin re-hashear).
     */
    public static function fromHash(string $hash): self
    {
        $instance = new self($hash);
        return $instance;
    }

    /**
     * Verifica si el texto plano coincide con el hash almacenado.
     */
    public function verifyPlain(string $plain): bool
    {
        return password_verify($plain, $this->value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserPassword $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
