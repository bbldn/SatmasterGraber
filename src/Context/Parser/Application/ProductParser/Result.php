<?php

namespace App\Context\Parser\Application\ProductParser;

class Result
{
    private ?int $id = null;

    private ?string $name = null;

    /**
     * @var string[]|null
     *
     * @psalm-param list<string>|null
     */
    private ?array $images = null;

    private ?string $description = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Result
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Result
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param array|null $images
     * @return Result
     */
    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Result
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}