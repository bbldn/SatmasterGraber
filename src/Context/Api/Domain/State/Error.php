<?php

namespace App\Context\Api\Domain\State;

class Error implements State
{
    use StateTrait;

    private string $message;

    private ?int $code;

    /**
     * @param string $message
     * @param int|null $code
     */
    public function __construct(
        string $message,
        ?int $code = null
    )
    {
        $this->message = $message;
        $this->code = $code;
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
     * @return Error
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCode(): ?int
    {
        return $this->code;
    }

    /**
     * @param int|null $code
     * @return Error
     */
    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public static function getStep(): string
    {
        return 'error';
    }
}