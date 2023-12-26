<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
#[UniqueEntity(
    'title',
    message: 'Ce titre existe déjà.'
)]
#[Vich\Uploadable]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotNull(
        message: "ce champ ne peut pas être nul.",
        payload: ['severity' => 'error']
    )]
    #[Assert\NotBlank(
        message: "Le renseignement de ce champ est obligatoire.",
        payload: ['severity' => 'error']
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "La valeur renseignée doit faire moins de 255 caractères."
    )]
    #[Assert\NotEqualTo(
        value: 'Test',
        message: "Le nom ne doit pas être {{ compared_value }}.",)]
    private $title = null;



    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: "Le renseignement de ce champ est obligatoire."
        ),
        new Assert\Length(
            min: 20,
            minMessage: "La valeur renseignée doit faire plus de 20 caractères.",
        )
        ])]
    #[Assert\Regex(
        pattern: '/plus belle la vie/',
        match: false,
        message: 'On parle de vraies séries ici',
        payload: ['severity' => 'warning'],
    )]
    private $synopsis = null;



    #[ORM\Column(nullable: true)]
    #[Assert\Url(
        message: "Merci de renseigner une adresse url valide."
    )]
    private ?string $officialWebsite = null;



    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $poster = null;



    #[Vich\UploadableField(mapping: 'poster_file', fileNameProperty: 'poster')]
    #[Assert\File(
        maxSize: '2M',
        maxSizeMessage: 'Le volume du fichier doit faire inférieur à 2M',
        extensions: ['jpeg', 'png', 'jpg', 'webp'],
        extensionsMessage: 'Les extensions autorisées sont .jpg, .png, .webp et .jpg',
    )]
    #[Assert\Image(
        minWidth: 300,
        maxWidth: 3000,
        minHeight: 100,
        maxHeight: 1000,
        allowPortrait: false,
        allowPortraitMessage: 'Les images au format portrait ne sont pas autorisées.'
    )]
    private ?File $posterFile = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;



    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;



    #[ORM\OneToMany(mappedBy: 'program', targetEntity: Season::class)]
    private Collection $seasons;



    #[ORM\ManyToMany(targetEntity: Actor::class, mappedBy: 'programs')]
    private Collection $actors;



    #[ORM\Column(length: 255)]
    private ?string $programSlug = null;



    #[ORM\ManyToOne(inversedBy: 'programs')]
    private ?User $owner = null;



    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'watchlist')]
    private Collection $viewers;



    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->actors = new ArrayCollection();
        $this->viewers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): static
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->setProgram($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): static
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getProgram() === $this) {
                $season->setProgram(null);
            }
        }

        return $this;
    }

    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeProgram($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->programSlug;
    }

    public function setSlug(string $programSlug): static
    {
        $this->programSlug = $programSlug;

        return $this;
    }

     public function getPosterFile(): ?File
     {
          return $this->posterFile;
     }

     public function setPosterFile(File $image = null): Program
     {
        $this->posterFile = $image;
        if ($image) {
          $this->updatedAt = new DateTime('now');
        }

          return $this;
     }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getViewers(): Collection
    {
        return $this->viewers;
    }

    public function addViewer(User $user): self
    {
        if (!$this->viewers->contains($user)) {
            $this->viewers->add($user);
            $user->addToWatchlist($this);
        }

        return $this;
    }

    public function removeViewer(User $user): self
    {
        if ($this->viewers->removeElement($user)) {
            $user->removeFromWatchlist($this);
        }

        return $this;
    }

    /**
     * Get the value of officialWebsite
     */ 
    public function getOfficialWebsite()
    {
        return $this->officialWebsite;
    }

    /**
     * Set the value of officialWebsite
     *
     * @return  self
     */ 
    public function setOfficialWebsite($officialWebsite)
    {
        $this->officialWebsite = $officialWebsite;

        return $this;
    }
}
