<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert; 



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(message: 'Username should not be blank')]
    #[Assert\Length(min: 4, max: 180, minMessage: 'Username should be at least 4 characters long')]
    #[ORM\Column(length: 180)]

    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]

    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */

    #[ORM\Column]
   
    #[Assert\NotBlank(message: 'Password should not be blank')]
    #[Assert\Length(min: 8, minMessage: 'Password should be at least 8 characters long')]
    private ?string $password = null;





    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    #[Assert\NotBlank(message: 'Phone number should not be blank')]
    #[Assert\Regex(
        pattern: '/^\+?[0-9]{1,4}?[-.\\s]?(\(?\d{1,3}?\)?[-.\\s]?)?[\d\s.-]{3,15}$/',
        message: 'Please provide a valid phone number.'
    )]
    private ?string $phoneNumber = null;
    
    #[Assert\NotBlank(message: 'Email should not be blank')]
    #[Assert\Email(message: 'Please provide a valid email address')]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $email = null;

    #[Assert\NotBlank(message: 'Address should not be blank')]
    #[Assert\Length(max: 255, maxMessage: 'Address should not be longer than 255 characters')]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $address = null;


    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserTheme::class, cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private Collection $userThemes;



    public function __construct()
    {
        $this->userThemes = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
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
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
    

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }








    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }



    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }





    public function getUserThemes () : Collection  {
        return $this->userThemes;
    }




    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
