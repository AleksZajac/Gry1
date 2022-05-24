<?php
/*
 * FavoriteMovies Entity
 */

namespace App\Entity;

use App\Repository\FavoriteGamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FAvoriteMovies claass.
 *
 * @ORM\Table(name="favorite_games")
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteGamesRepository")
 */
class FavoriteGames
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-optionsda.
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 10;
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, inversedBy="favoriteGames")
     */
    private $game;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="favoriteMovies", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * Favorite constructor.
     */
    public function __construct()
    {
        $this->game = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGame(): Collection
    {
        return $this->game;
    }

    /**
     * @return $this
     */
    public function addGame(Game $game): self
    {
        if (!$this->game->contains($game)) {
            $this->game[] = $game;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeGame(Game $game): self
    {
        $this->game->removeElement($game);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
