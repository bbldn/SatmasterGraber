<?php

namespace App\Context\Front\Domain\Message;

class GenerateArchive
{
    private string $userId;

    private string $sourceCategoryUrl;

    private ?int $destinationCategoryId = null;

    private ?string $destinationImagesPath = null;

    /**
     * @param string $userId
     * @param string $sourceCategoryUrl
     */
    public function __construct(
        string $userId,
        string $sourceCategoryUrl
    )
    {
        $this->userId = $userId;
        $this->sourceCategoryUrl = $sourceCategoryUrl;
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
     * @return GenerateArchive
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
     * @return GenerateArchive
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
     * @return GenerateArchive
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
     * @return GenerateArchive
     */
    public function setDestinationImagesPath(?string $destinationImagesPath): self
    {
        $this->destinationImagesPath = $destinationImagesPath;

        return $this;
    }
}