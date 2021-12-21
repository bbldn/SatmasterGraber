<?php

namespace App\Domain\Parser\Application\Command;

use App\Domain\Common\Application\CommandBus\Command;

class ParseCategoryProductsByCategoryURL implements Command
{
    private string $url;

    /** @var null|callable  */
    private $onInit;

    /** @var null|callable  */
    private $onStep;

    /**
     * @param string $url
     * @param callable|null $onInit
     * @param callable|null $onStep
     */
    public function __construct(
        string $url,
        ?callable $onInit = null,
        ?callable $onStep = null
    )
    {
        $this->url = $url;
        $this->onInit = $onInit;
        $this->onStep = $onStep;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return callable|null
     */
    public function getOnInit(): ?callable
    {
        return $this->onInit;
    }

    /**
     * @return callable|null
     */
    public function getOnStep(): ?callable
    {
        return $this->onStep;
    }
}