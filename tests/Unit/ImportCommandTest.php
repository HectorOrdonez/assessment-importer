<?php
namespace App\Test;

use App\Command\ImportCommand;
use App\Importer\Exception\ImporterException;
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
     * @expectedExceptionMessage Not enough arguments (missing: "xml, output")
     */
    public function testRunningImportWithoutXmlThrowsAnException()
    {
        $command = new ImportCommand();
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
        $command = new ImportCommand();
        $commandTester = new CommandTester($command);

        $response = $commandTester->execute(['xml' => 'test']);

        $this->assertEquals(0, $response);
    }

    /**
     * @depends testRunningImportWithoutXmlThrowsAnException
     */
    public function testImportCommandPassesXmlAndOutputToImporter()
    {
        $xmlPath = 'some_path';
        $outPath = 'another_path';

        $importer = $this->getImporterMock();
        $importer->shouldReceive('setXmlPath')->once()->with($xmlPath);
        $importer->shouldReceive('setOutputName')->once()->with($outPath);
        $importer->shouldReceive('run')->once();

        $command = new ImportCommand();
        $command->setImporter($importer);
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'xml' => $xmlPath,
            'output' => $outPath,
        ]);
    }

    public function testImportCommandCatchesImporterExceptionsAndTurnsThemIntoWarningMessages()
    {
        $message = 'Critical exception text';

        $importer = $this->getImporterMock();
        $importer->shouldReceive('setXmlPath');
        $importer->shouldReceive('setOutputName');
        $importer->shouldReceive('run')->once()->andThrow(new ImporterException($message));

        $command = new ImportCommand();
        $command->setImporter($importer);
        $commandTester = new CommandTester($command);

        $commandTester->execute(['xml' => 'something', 'output' => 'else']);
    }

    /**
     * @return MockInterface|Importer
     */
    private function getImporterMock()
    {
        return \Mockery::mock(Importer::class);
    }
}
