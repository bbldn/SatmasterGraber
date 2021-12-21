<?php

namespace App\Domain\Api\Domain\State;

class Process implements State
{
    use StateTrait;

    private int $percent;

    private string $message;

    /**
     * @param int $percent
     * @param string $message
     */
    public function __construct(
        int $percent,
        string $message
    )
    {
        $this->percent = $percent;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getPercent(): int
    {
        return $this->percent;
    }

    /**
     * @param int $percent
     * @return self
     */
    public function setPercent(int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public static function getStep(): string
    {
        return 'process';
    }
}