<?php
namespace App\Test;

use App\Command\Import;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportTest extends TestCase
{
    public function testBasics()
    {
        // Prepare
        $command = new Import();
        $commandTester = new CommandTester($command);

        // Action
        $response = $commandTester->execute([]);

        // Assert
        $this->assertEquals(0, $response);
    }
}
