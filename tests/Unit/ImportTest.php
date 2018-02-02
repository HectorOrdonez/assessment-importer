<?php
namespace App\Test;

use App\Command\Import;
use App\Importer\Importer;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class ImportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments (missing: "xml")
     */
    public function testRunningImportWithoutXmlThrowsAnException()
    {
        // Prepare
        $command = new Import();
        $commandTester = new CommandTester($command);

        // Action
        $response = $commandTester->execute([]);

        // Assert
        $this->assertEquals(0, $response);
    }

    /**
     * @depends testRunningImportWithoutXmlThrowsAnException
     */
    public function testRunningImportPassesXmlToTheImporter()
    {
        // Prepare
        $importer = \Mockery::mock(Importer::class);
        $command = new Import();
        $command->setImporter($importer);
        $commandTester = new CommandTester($command);
        $xmlPath = 'some_path';

        // Assert
        $importer->shouldReceive('setXmlPath')->once()->with($xmlPath);

        // Action
        $commandTester->execute(['xml' => $xmlPath]);
    }
}
