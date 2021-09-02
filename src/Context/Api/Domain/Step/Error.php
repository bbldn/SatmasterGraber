<?php

namespace App\Context\Api\Domain\Step;

class Error implements Step
{
    use StepTrait;

    private string $text;

    private ?int $code;

    /**
     * @param string $text
     * @param int|null $code
     */
    public function __construct(
        string $text,
        ?int $code = null
    )
    {
        $this->text = $text;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Error
     */
    public function setText(string $text): self
    {
        $this->text = $text;

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
    public function getStep(): string
    {
        return 'error';
    }
}