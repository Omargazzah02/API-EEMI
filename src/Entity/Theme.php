<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'The name should not be blank')]
    #[Assert\Length(max: 255, maxMessage: 'The name should not exceed 255 characters')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000, maxMessage: 'The description should not exceed 1000 characters')]

    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Price should not be blank')]
    #[Assert\Positive(message: 'Price should be a positive number')]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Number of users should not be blank')]
    #[Assert\PositiveOrZero(message: 'Number of users should be zero or a positive number')]
    private ?int $numberOfUsers = null;


    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: UserTheme::class, cascade: ['persist', 'remove'])]
    private Collection $userThemes;


    public function __construct()
    {
        $this->userThemes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getNumberOfUsers(): ?int
    {
        return $this->numberOfUsers;
    }

    public function setNumberOfUsers(int $numberOfUsers): static
    {
        $this->numberOfUsers = $numberOfUsers;

        return $this;
    }

    public function getUserTheme() : Collection {
return $this->userThemes;
    }
}
