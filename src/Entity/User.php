<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Validator\Constraints as Asserts;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cette adresse mail.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(
        message: "L'adresse mail renseignée {{ value }} n'est pas valide.",
    )]
    private ?string $email = null;



    #[ORM\Column(type: 'json')]
    private array $roles = [];



    #[ORM\Column(length: 100)]
    #[Assert\Regex(        
        pattern: '/\d/',
        match: false,
        message: 'Votre prénom ne doit pas comporter de chiffre.',
    )]
    private ?string $firstName = null;



    #[ORM\Column]
    private ?string $password = null;



    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    private Collection $comments;



    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Program::class)]
    private Collection $programs;



    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;



    #[ORM\ManyToMany(targetEntity: Program::class, inversedBy: 'viewers')]
    #[ORM\JoinTable(name:'watchlist')]
    private Collection $watchlist;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'fans')]
    #[ORM\JoinTable(name:'favorite_actors')]
    private Collection $favoriteActors;



    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->programs = new ArrayCollection();
        $this->watchlist = new ArrayCollection();
        $this->favoriteActors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
            $program->setOwner($this);
        }

        return $this;
    }

    public function removeProgram(Program $program): static
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getOwner() === $this) {
                $program->setOwner(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Program>
     */
    public function getWatchlist(): Collection
    {
        return $this->watchlist;
    }

    public function addToWatchlist(Program $program): self
    {
        if (!$this->watchlist->contains($program)) {
            $this->watchlist[] = $program;
        }

        return $this;
    }

    public function removeFromWatchlist(Program $program): self
    {
        $this->watchlist->removeElement($program);

        return $this;
    }

    // public function isInWatchlist(Program $program): bool
    // {
    //     return $this->watchlist->contains($program);
    // }

    /**
     * @return Collection<int, Actor>
     */
    public function getFavoriteActors(): Collection
    {
        return $this->favoriteActors;
    }

    public function addFavoriteActor(Actor $favoriteActor): static
    {
        if (!$this->favoriteActors->contains($favoriteActor)) {
            $this->favoriteActors->add($favoriteActor);
        }

        return $this;
    }

    public function removeFavoriteActor(Actor $favoriteActor): static
    {
        $this->favoriteActors->removeElement($favoriteActor);

        return $this;
    }
}
