<?php
namespace App\Importer\Support;

class FileWriter
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * FileWriter constructor.
     *
     * @param $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function writeHeaders()
    {
        $columns = [
            'name',
            'email',
            'phone',
            'dob',
            'credit card type',
            'interests space separated',
        ];

        return $this->writeln(implode(',', $columns));
    }

    /**
     * Writes on the file the given line
     *
     * If no flag tells otherwise, this will remove any previous file with same name
     *
     * @param string $line
     * @param int $flag
     * @return int
     */
    public function writeln($line, $flag = 0)
    {
        return file_put_contents($this->fileName, "{$line}\r\n", $flag);
    }
}
