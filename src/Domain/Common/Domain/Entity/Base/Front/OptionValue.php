<?php

namespace App\Domain\Common\Domain\Entity\Base\Front;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Common\Infrastructure\Repository\Base\Front\OptionValueRepository;

/**
 * @ORM\Table(name="`oc_option_value`")
 * @ORM\Entity(repositoryClass=OptionValueRepository::class)
 */
class OptionValue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="`option_value_id`")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Option::class)
     * @ORM\JoinColumn(name="`option_id`", referencedColumnName="`option_id`")
     */
    private ?Option $option = null;

    /**
     * @ORM\Column(type="string", name="`image`", length=255)
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="integer", name="`sort_order`")
     */
    private ?int $sortOrder = null;

    /**
     * @var Collection|OptionValueDescription[]
     * @ORM\OneToMany(
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true,
     *     mappedBy="optionValue",
     *     cascade={"persist", "remove"},
     *     targetEntity=OptionValueDescription::class
     * )
     *
     * @psalm-var Collection<int, OptionValueDescription>
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
     * @return OptionValue
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Option|null
     */
    public function getOption(): ?Option
    {
        return $this->option;
    }

    /**
     * @param Option|null $option
     * @return OptionValue
     */
    public function setOption(?Option $option): self
    {
        $this->option = $option;

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
     * @return OptionValue
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
     * @param int|null $sortOrder
     * @return OptionValue
     */
    public function setSortOrder(?int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * @return OptionValueDescription[]|Collection
     *
     * @psalm-return Collection<int, OptionValueDescription>
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }
}