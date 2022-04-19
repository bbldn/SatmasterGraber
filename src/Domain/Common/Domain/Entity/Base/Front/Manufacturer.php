<?php

namespace App\Domain\Common\Domain\Entity\Base\Front;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Common\Infrastructure\Repository\Base\Front\ManufacturerRepository;

/**
 * @ORM\Table(name="`oc_manufacturer`")
 * @ORM\Entity(repositoryClass=ManufacturerRepository::class)
 */
class Manufacturer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="`manufacturer_id`")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", name="`name`", length=64)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", name="`image`", length=255, nullable=true)
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="integer", name="`sort_order`")
     */
    private ?int $sortOrder = null;

    /**
     * @var Collection|Shop[]
     * @ORM\ManyToMany(targetEntity=Shop::class, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(
     *     name="`oc_manufacturer_to_store`",
     *     inverseJoinColumns={@ORM\JoinColumn(name="`store_id`", referencedColumnName="`store_id`")},
     *     joinColumns={@ORM\JoinColumn(name="`manufacturer_id`", referencedColumnName="`manufacturer_id`")}
     * )
     *
     * @psalm-var Collection<int, Shop>
     */
    private Collection $shops;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Manufacturer
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return Manufacturer
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

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
     * @return Manufacturer
     */
    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * @return Shop[]|Collection
     *
     * @psalm-return Collection<int, Shop>
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }
}