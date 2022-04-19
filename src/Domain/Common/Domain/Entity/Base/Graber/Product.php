<?php

namespace App\Domain\Common\Domain\Entity\Base\Graber;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Common\Infrastructure\Repository\Base\Graber\ProductRepository;

/**
 * @ORM\Table(name="`products`")
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="`parser_id`")
     */
    private ?int $parserId = null;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="`front_id`")
     */
    private ?int $frontId = null;

    /**
     * @return int|null
     */
    public function getParserId(): ?int
    {
        return $this->parserId;
    }

    /**
     * @param int|null $parserId
     * @return Product
     */
    public function setParserId(?int $parserId): self
    {
        $this->parserId = $parserId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFrontId(): ?int
    {
        return $this->frontId;
    }

    /**
     * @param int|null $frontId
     * @return Product
     */
    public function setFrontId(?int $frontId): self
    {
        $this->frontId = $frontId;

        return $this;
    }
}