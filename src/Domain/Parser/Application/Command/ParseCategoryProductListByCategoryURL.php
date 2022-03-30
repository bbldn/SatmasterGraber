<?php

namespace App\Domain\Parser\Application\Command;

use Closure;
use BBLDN\CQRS\CommandBus\Command;
use BBLDN\CQRS\CommandBus\Annotation as CQRS;
use App\Domain\Parser\Application\CommandHandler\ParseCategoryProductListByCategoryURLHandler\CommandHandler;

/**
 * @CQRS\CommandHandler(class=CommandHandler::class)
 */
class ParseCategoryProductListByCategoryURL implements Command
{
    private string $url;

    /** @psalm-var null|Closure(int):void  */
    private ?Closure $onInit;

    /** @psalm-var null|Closure():void  */
    private ?Closure $onStep;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return Closure|null
     *
     * @psalm-return null|Closure(int):void
     */
    public function getOnInit(): ?Closure
    {
        return $this->onInit;
    }

    /**
     * @param Closure|null $onInit
     * @return ParseCategoryProductListByCategoryURL
     *
     * @psalm-param null|Closure(int):void $onInit
     */
    public function setOnInit(?Closure $onInit): self
    {
        $this->onInit = $onInit;

        return $this;
    }

    /**
     * @return Closure|null
     *
     * @psalm-return null|Closure():void
     */
    public function getOnStep(): ?Closure
    {
        return $this->onStep;
    }

    /**
     * @param Closure|null $onStep
     * @return ParseCategoryProductListByCategoryURL
     *
     * @psalm-param null|Closure():void $onInit
     */
    public function setOnStep(?Closure $onStep): self
    {
        $this->onStep = $onStep;

        return $this;
    }
}