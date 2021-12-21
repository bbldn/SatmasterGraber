<?php

namespace App\Domain\Api\Application\Command;

use App\Domain\Common\Application\CommandBus\Command;

class StartProcess implements Command
{
    private string $userId;

    private string $sourceCategoryUrl;

    private ?int $destinationCategoryId;

    private ?string $destinationImagesPath;

    /**
     * @param string $userId
     * @param string $sourceCategoryUrl
     * @param int|null $destinationCategoryId
     * @param string|null $destinationImagesPath
     */
    public function __construct(
        string $userId,
        string $sourceCategoryUrl,
        ?int $destinationCategoryId,
        ?string $destinationImagesPath
    )
    {
        $this->userId = $userId;
        $this->sourceCategoryUrl = $sourceCategoryUrl;
        $this->destinationCategoryId = $destinationCategoryId;
        $this->destinationImagesPath = $destinationImagesPath;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return StartProcess
     */
    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceCategoryUrl(): string
    {
        return $this->sourceCategoryUrl;
    }

    /**
     * @param string $sourceCategoryUrl
     * @return StartProcess
     */
    public function setSourceCategoryUrl(string $sourceCategoryUrl): self
    {
        $this->sourceCategoryUrl = $sourceCategoryUrl;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDestinationCategoryId(): ?int
    {
        return $this->destinationCategoryId;
    }

    /**
     * @param int|null $destinationCategoryId
     * @return StartProcess
     */
    public function setDestinationCategoryId(?int $destinationCategoryId): self
    {
        $this->destinationCategoryId = $destinationCategoryId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDestinationImagesPath(): ?string
    {
        return $this->destinationImagesPath;
    }

    /**
     * @param string|null $destinationImagesPath
     * @return StartProcess
     */
    public function setDestinationImagesPath(?string $destinationImagesPath): self
    {
        $this->destinationImagesPath = $destinationImagesPath;

        return $this;
    }
}