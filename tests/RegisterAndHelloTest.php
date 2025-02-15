<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../src/run-command.php');

final class RegisterAndHelloTest extends TestCase
{
    public function testUserNotFound(): void
    {
        setTestDb();
        $this->assertEquals(
            [ "User not found or wrong password" ],
            runCommand([], ['hello'])
        );
    }
    public function testUserRegisteredAndFound(): void
    {
        setTestDb();
        $lastInsertId = intval(runCommand([ 'adminParty' => true ], ['register', 'someuser', 'somepass'])[0]);
        // right user and pass
        setUser('someuser', 'somepass');
        $this->assertEquals(
            [
                'user' => [
                    'id' => $lastInsertId,
                    'username' => 'someuser',
                ],
                'adminParty' => true
            ],
            getContext()
        );

        $this->assertEquals(
            [ "Hello someuser, your userId is $lastInsertId" ],
            runCommand(getContext(), ['hello'])
        );
    }
    public function testUserRegisteredLoginWrongPass(): void
    {
        setTestDb();
        $lastInsertId = intval(runCommand([ 'adminParty' => true ], ['register', 'someuser', 'somepass'])[0]);

        // wrong pass
        setUser('someuser', 'wrongpass');
        $this->assertEquals([
                'user' => null,
                'adminParty' => true
            ],
            getContext()
        );

        $this->assertEquals(
            [ "User not found or wrong password" ],
            runCommand(getContext(), ['hello'])
        );
    }
    public function testUserRegisteredLoginWrongUsername(): void
    {
        setTestDb();
        $lastInsertId = intval(runCommand([ 'adminParty' => true ], ['register', 'someuser', 'somepass'])[0]);

        // wrong user
        setUser('wronguser', 'somepass');
        $this->assertEquals([
                'user' => null,
                'adminParty' => true
            ],
            getContext()
        );

        $this->assertEquals(
            [ "User not found or wrong password" ],
            runCommand(getContext(), ['hello'])
        );

    }
}




