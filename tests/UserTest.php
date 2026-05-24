<?php
// tests/UserTest.php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserNameNotEmpty()
    {
        $this->expectException(Domain\Exceptions\InvalidUserNameException::class);
        new Domain\ValueObjects\UserName("");
    }
}
