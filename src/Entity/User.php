<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields = {"email"},
 * message = "The email you entered is already used."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;


    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8 ,minMessage="Your password must be at least 8 characters long.")
     * @Assert\EqualTo(propertyPath = "validpassword",message="You did not enter the same password.")
     */
    private $password;
    /*
     * @Assert\EqualTo(propertyPath = "password",message="You did not enter the same password.")
     */
    private $validpassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getValidpassword(): ?string
    {
        return $this->validpassword;
    }

    public function setValidpassword(string $validpassword): self
    {
        $this->validpassword = $validpassword;

        return $this;
    }

    public function getRoles(): array
    {
        //$roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_ADMIN';
        //$roles[] = 'ROLE_USER';

       // return $roles->toArray();
        return $this->roles;
    }

    public function getDescriptionRoles(): string
    {
        $role = $this->roles;
        if($role==array('ROLE_USER')){
            return 'USER';
        } elseif($role==array('ROLE_ADMIN')) {
            return 'ADMIN';
        } else {
            return 'ADMIN/USER';
        }
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
    }
}
