<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "t'as oublié de me renseigner !"
    )]
    #[Assert\Length(
        min: 3,
        minMessage: "C'est beaucoup trop court !",
        max: 255,
        maxMessage: "C'est beaucoup trop long !"
    )]
    private $name;



    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Program::class)]
    private ?Collection $programs;



    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }


    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
            $program->setCategory($this);
        }
        return $this;
    }

    public function removeProgram(Program $program): self
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getCategory() === $this) {
                $program->setCategory(null);
            }
        }
    
        return $this;
    }
}
