<?php

namespace App\Context\Api\Application\Common\State;

use App\Context\Api\Domain\State\State;
use App\Context\Api\Domain\State\NotRunning;

class File
{
    private string $fileName;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        if (true === file_exists($this->fileName)) {
            unlink($this->fileName);
        }
    }

    /**
     * @return void
     */
    public function create(): void
    {
        $this->whiteState(new NotRunning());
    }

    /**
     * @param State $state
     * @return void
     */
    public function whiteState(State $state): void
    {
        if (false === file_exists($this->fileName)) {
            $dirName = dirname($this->fileName);
            if (false === is_dir($dirName)) {
                mkdir($dirName, 0777, true);
            }
        }

        file_put_contents($this->fileName, json_encode($state));
    }

    /**
     * @return State
     */
    public function readState(): State
    {
        if (false === file_exists($this->fileName)) {
            $this->create();
        }

        $data = json_decode(file_get_contents($this->fileName), true);
        if (false === $data) {
            $data = [];
        }

        return Hydrator::toStep($data);
    }
}