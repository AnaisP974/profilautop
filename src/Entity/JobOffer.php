<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $salary = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactEmail = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $applicationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $app_user = null;

    /**
     * @var Collection<int, LinkedInMessage>
     */
    #[ORM\OneToMany(targetEntity: LinkedInMessage::class, mappedBy: 'jobOffer', orphanRemoval: true)]
    private Collection $linkedInMessages;

    #[ORM\ManyToOne(inversedBy: 'jobOffer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CoverLetter $coverLetter = null;

    public function __construct()
    {
        $this->linkedInMessages = new ArrayCollection();
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

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getContactPerson(): ?string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(?string $contactPerson): static
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getApplicationDate(): ?\DateTimeInterface
    {
        return $this->applicationDate;
    }

    public function setApplicationDate(?\DateTimeInterface $applicationDate): static
    {
        $this->applicationDate = $applicationDate;

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

    public function getAppUser(): ?User
    {
        return $this->app_user;
    }

    public function setAppUser(?User $app_user): static
    {
        $this->app_user = $app_user;

        return $this;
    }

    /**
     * @return Collection<int, LinkedInMessage>
     */
    public function getLinkedInMessages(): Collection
    {
        return $this->linkedInMessages;
    }

    public function addLinkedInMessage(LinkedInMessage $linkedInMessage): static
    {
        if (!$this->linkedInMessages->contains($linkedInMessage)) {
            $this->linkedInMessages->add($linkedInMessage);
            $linkedInMessage->setJobOffer($this);
        }

        return $this;
    }

    public function removeLinkedInMessage(LinkedInMessage $linkedInMessage): static
    {
        if ($this->linkedInMessages->removeElement($linkedInMessage)) {
            // set the owning side to null (unless already changed)
            if ($linkedInMessage->getJobOffer() === $this) {
                $linkedInMessage->setJobOffer(null);
            }
        }

        return $this;
    }

    public function getCoverLetter(): ?CoverLetter
    {
        return $this->coverLetter;
    }

    public function setCoverLetter(?CoverLetter $coverLetter): static
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }
}
