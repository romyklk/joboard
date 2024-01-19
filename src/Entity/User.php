<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cette adresse email.')]
#[UniqueEntity(fields: ['username'], message: 'Ce nom d\'utilisateur est déjà utilisé. Veuillez en choisir un autre.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir une adresse email.')]
    #[Assert\Email(message: 'Veuillez saisir une adresse email valide.')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir un mot de passe.')]
    #[Assert\Length(min: 6, minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères.')]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veullez preciser si vous êtes un candidat ou une entreprise.')]
    private ?string $status = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir un nom d\'utilisateur.')]
    #[Assert\Length(min: 3, minMessage: 'Votre nom d\'utilisateur doit contenir au moins {{ limit }} caractères.', max: 50, maxMessage: 'Votre nom d\'utilisateur ne peut pas contenir plus de {{ limit }} caractères.')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/', message: 'Votre nom d\'utilisateur ne peut contenir que des lettres, des chiffres et des underscores.')]
    private ?string $username = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserProfil $userProfil = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?EntrepriseProfil $entrepriseProfil = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Application::class)]
    private Collection $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getUserProfil(): ?UserProfil
    {
        return $this->userProfil;
    }

    public function setUserProfil(UserProfil $userProfil): static
    {
        // set the owning side of the relation if necessary
        if ($userProfil->getUser() !== $this) {
            $userProfil->setUser($this);
        }

        $this->userProfil = $userProfil;

        return $this;
    }

    public function getEntrepriseProfil(): ?EntrepriseProfil
    {
        return $this->entrepriseProfil;
    }

    public function setEntrepriseProfil(EntrepriseProfil $entrepriseProfil): static
    {
        // set the owning side of the relation if necessary
        if ($entrepriseProfil->getUser() !== $this) {
            $entrepriseProfil->setUser($this);
        }

        $this->entrepriseProfil = $entrepriseProfil;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setUser($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getUser() === $this) {
                $application->setUser(null);
            }
        }

        return $this;
    }
}
