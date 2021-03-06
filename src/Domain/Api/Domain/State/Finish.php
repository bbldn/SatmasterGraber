<?php

namespace App\Domain\Api\Domain\State;

class Finish implements State
{
    use StateTrait;

    private string $url;

    private string $message;

    /**
     * @param string $url
     * @param string $message
     */
    public function __construct(
        string $url,
        string $message
    )
    {
        $this->url = $url;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Finish
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

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
     * @return Finish
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
        return 'finish';
    }
}