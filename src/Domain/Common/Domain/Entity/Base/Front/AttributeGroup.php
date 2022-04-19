<?php

namespace App\Domain\Common\Domain\Entity\Base\Front;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Common\Infrastructure\Repository\Base\Front\AttributeGroupRepository;

/**
 * @ORM\Table(name="`oc_attribute_group`")
 * @ORM\Entity(repositoryClass=AttributeGroupRepository::class)
 */
class AttributeGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="`attribute_group_id`")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer", name="`sort_order`")
     */
    private ?int $sortOrder = null;

    /**
     * @var Collection|AttributeGroupDescription[]
     * @ORM\OneToMany(
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true,
     *     mappedBy="attributeGroup",
     *     cascade={"persist", "remove"},
     *     targetEntity=AttributeGroupDescription::class
     * )
     *
     * @psalm-var Collection<int, AttributeGroupDescription>
     */
    private Collection $descriptions;

    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AttributeGroup
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     * @return AttributeGroup
     */
    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * @return Collection|AttributeGroupDescription[]
     *
     * @psalm-return Collection<int, AttributeGroupDescription>
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }
}