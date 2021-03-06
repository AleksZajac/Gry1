<?php
/**
 * User Entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class User implements UserInterface
{
    /**
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 10;

    /**
     * USER.
     *
     * @var string
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     * ADMIN.
     *
     * @var string
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Email.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * Password.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="6",
     *     max="255",
     * )
     */
    private $password;

    /**
     * Roles.
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * UserProfile.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\UserProfile", inversedBy="user", cascade={"persist", "remove"})
     */
    private $userprofile;

    /**
     * @ORM\OneToOne(targetEntity=FavoriteGames::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $favoriteGames;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->userprofile = new ArrayCollection();
    }

    /**
     * Getter fo idUser.
     *
     * @return int|null idUser
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Email.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email Email
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter for password.
     *
     * @return string|null Password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for Password.
     *
     * @param string $password Password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using bcrypt or argon
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Getter for the Roles.
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * Setter for the Roles.
     *
     * @param array $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     *
     * @see UserInterface
     *
     * @return string Username
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return ArrayCollection|UserProfile
     */
    public function getUserprofile()
    {
        return $this->userprofile;
    }

    /**
     * @return User
     */
    public function setUserprofile(UserProfile $userprofile): self
    {
        $this->userprofile = $userprofile;
        if ($this !== $userprofile->getUser()) {
            $userprofile->setUser($this);
        }

        return $this;
    }

    public function getFavoriteGames(): ?FavoriteGames
    {
        return $this->favoriteGames;
    }

    public function setFavoriteGames(?FavoriteGames $favoriteGames): self
    {
        // unset the owning side of the relation if necessary
        if ($favoriteGames === null && $this->favoriteGames !== null) {
            $this->favoriteGames->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($favoriteGames !== null && $favoriteGames->getUser() !== $this) {
            $favoriteGames->setUser($this);
        }

        $this->favoriteGames = $favoriteGames;

        return $this;
    }
}
