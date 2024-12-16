<?php

namespace App\Entity;

use App\Repository\UserThemeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserThemeRepository::class)]
class UserTheme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $subscriptionDate = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userThemes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Theme::class, inversedBy: 'userThemes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Theme $theme = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionDate(): ?\DateTimeInterface
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(?\DateTimeInterface $subscriptionDate): static
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    public function setUser(User $user) {
        $this->user = $user;

    }
    public function setTheme(Theme $theme) {
        $this->theme = $theme;

    }

    public function getTheme()  : ?Theme{
       return $this->theme;

    }
    public function getUser() : ?User {
      return $this->user;

    }

    
}
