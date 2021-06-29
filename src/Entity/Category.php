<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    public ?string $name;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;
    //ici ici

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="categories")
     */
    private  $parent ;

   //orphanRemoval=true
    /**
     * @ORM\OneToMany(targetEntity=Category::class,mappedBy="parent",cascade={"persist","remove"})
     */
    private $categories;
    //orphanRemoval=true


    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="category",cascade={"persist","remove"})
     */
    private  $services;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }



    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->services = new ArrayCollection();



    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name):? self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }


    /**
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     */
    public function setParent(Category $parent): void
    {
        $this->parent = $parent;
    }





    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setCategory($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getCategory() === $this) {
                $service->setCategory(null);
            }
        }

        return $this;
    }
}
