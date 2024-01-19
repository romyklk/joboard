<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir un titre.')]
    #[Assert\Length(min: 5, minMessage: 'Le titre doit contenir au moins {{ limit }} caractères.', max: 80, maxMessage: 'Le titre doit contenir au maximum {{ limit }} caractères.')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir une description.')]
    #[Assert\Length(min: 5, minMessage: 'La description doit contenir au moins {{ limit }} caractères.', max: 255, maxMessage: 'La description doit contenir au maximum {{ limit }} caractères.')]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez saisir un contenu.')]
    #[Assert\Length(min: 20, minMessage: 'Le contenu doit contenir au moins {{ limit }} caractères.')]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir un salaire.')]
    #[Assert\Positive(message: 'Le salaire doit être positif.')]
    #[Assert\Type(type: 'integer', message: 'Le salaire doit être un nombre entier.')]
    private ?int $salary = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir une localisation.')]
    #[Assert\Length(min: 2, minMessage: 'La localisation doit contenir au moins {{ limit }} caractères.', max: 100, maxMessage: 'La localisation doit contenir au maximum {{ limit }} caractères.')]
    private ?string $location = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContractType $contractType = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EntrepriseProfil $entreprise = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'offers')]
    private Collection $tags;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'Offer', targetEntity: Application::class)]
    private Collection $applications; 

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        //$this->createdAt = new \DateTimeImmutable();
        //$this->isActive = true;
        $this->applications = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initalizeSlug()
    {
        if(!$this->slug) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeIsActive()
    {
        if(!$this->isActive) {
            $this->isActive = true;
        }
    }

    #[ORM\PrePersist]
    public function initializeCreatedAt()
    {
        if(!$this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        }
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
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

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getContractType(): ?ContractType
    {
        return $this->contractType;
    }

    public function setContractType(?ContractType $contractType): static
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getEntreprise(): ?EntrepriseProfil
    {
        return $this->entreprise;
    }

    public function setEntreprise(?EntrepriseProfil $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

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
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }


}
