<?php

namespace App\Domain\Common\Domain\Entity\Base\Front;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Common\Infrastructure\Repository\Base\Front\CategoryDescriptionRepository;

/**
 * @ORM\Table(name="`oc_category_description`")
 * @ORM\Entity(repositoryClass=CategoryDescriptionRepository::class)
 */
class CategoryDescription
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="descriptions")
     * @ORM\JoinColumn(name="`category_id`", referencedColumnName="`category_id`")
     */
    private ?Category $category = null;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=Language::class)
     * @ORM\JoinColumn(name="`language_id`", referencedColumnName="`language_id`")
     */
    private ?Language $language = null;

    /**
     * @ORM\Column(type="string", name="`name`", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="text", name="`description`")
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", name="`meta_h1`", nullable=true, length=100)
     */
    private ?string $metaH1 = null;

    /**
     * @ORM\Column(type="string", name="`meta_title`", length=255)
     */
    private ?string $metaTitle = null;

    /**
     * @ORM\Column(type="string", name="`meta_keyword`", length=255)
     */
    private ?string $metaKeyword = null;

    /**
     * @ORM\Column(type="string", name="`meta_description`", length=255)
     */
    private ?string $metaDescription = null;

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return CategoryDescription
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @param Language|null $language
     * @return CategoryDescription
     */
    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

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
     * @return CategoryDescription
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

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
     * @return CategoryDescription
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaH1(): ?string
    {
        return $this->metaH1;
    }

    /**
     * @param string|null $metaH1
     * @return CategoryDescription
     */
    public function setMetaH1(?string $metaH1): self
    {
        $this->metaH1 = $metaH1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * @param string|null $metaTitle
     * @return CategoryDescription
     */
    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaKeyword(): ?string
    {
        return $this->metaKeyword;
    }

    /**
     * @param string|null $metaKeyword
     * @return CategoryDescription
     */
    public function setMetaKeyword(?string $metaKeyword): self
    {
        $this->metaKeyword = $metaKeyword;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param string|null $metaDescription
     * @return CategoryDescription
     */
    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }
}