<?php

namespace App\Domain\Parser\Application\Command;

use Closure;
use BBLDN\CQRSBundle\CommandBus\Command;
use BBLDN\CQRSBundle\CommandBus\Annotation as CQRS;
use App\Domain\Parser\Application\CommandHandler\ParserProductListByCategoryUrlHandler\CommandHandler;

/**
 * @CQRS\CommandHandler(class=CommandHandler::class)
 */
class ParserProductListByCategoryUrl implements Command
{
    private string $url;

    private ?int $categoryFrontId = null;

    /** @psalm-var null|Closure(int):void */
    private ?Closure $onInit = null;

    /** @psalm-var null|Closure():void */
    private ?Closure $onStep = null;

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
     * @return int|null
     */
    public function getCategoryFrontId(): ?int
    {
        return $this->categoryFrontId;
    }

    /**
     * @param int|null $categoryFrontId
     * @return ParserProductListByCategoryUrl
     */
    public function setCategoryFrontId(?int $categoryFrontId): self
    {
        $this->categoryFrontId = $categoryFrontId;

        return $this;
    }

    /**
     * @return null|Closure
     *
     * @psalm-return null|Closure(int):void
     */
    public function getOnInit(): ?Closure
    {
        return $this->onInit;
    }

    /**
     * @param null|Closure $onInit
     * @return ParserProductListByCategoryUrl
     *
     * @psalm-param null|Closure(int):void $onInit
     */
    public function setOnInit(?Closure $onInit): self
    {
        $this->onInit = $onInit;

        return $this;
    }

    /**
     * @return null|Closure
     *
     * @psalm-return null|Closure():void
     */
    public function getOnStep(): ?Closure
    {
        return $this->onStep;
    }

    /**
     * @param null|Closure $onStep
     * @return ParserProductListByCategoryUrl
     *
     * @psalm-param null|Closure():void $onStep
     */
    public function setOnStep(?Closure $onStep): self
    {
        $this->onStep = $onStep;

        return $this;
    }
}