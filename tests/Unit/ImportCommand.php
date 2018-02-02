<?php
namespace App\Test;

use App\Command\Import;
use App\Importer\Importer;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
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
        $command = new Import();
        $commandTester = new CommandTester($command);

        $response = $commandTester->execute([]);

        $this->assertEquals(0, $response);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments (missing: "output")
     */
    public function testRunningImportWithoutOutputThrowsAnException()
    {
        // @todo
    }

    /**
     * @depends testRunningImportWithoutXmlThrowsAnException
     */
    public function testImportCommandPassesXmlAndOutputToImporter()
    {
        /**
         * @var Importer|MockInterface $importer
         */
        $importer = \Mockery::mock(Importer::class);
        $command = new Import();
        $command->setImporter($importer);
        $commandTester = new CommandTester($command);
        $xmlPath = 'some_path';
        $outPath = 'another_path';

        $importer->shouldReceive('setXmlPath')->once()->with($xmlPath);
        $importer->shouldReceive('setOutputPath')->once()->with($outPath);

        $commandTester->execute(['xml' => $xmlPath]);
    }
}
