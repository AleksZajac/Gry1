<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table (name="game")
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $publisher;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * Release Date.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Assert\Date
     */
    private $releasedate;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="games")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="games")
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     */
    private $extensive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gametime;

    /**
     * @ORM\OneToOne(targetEntity=Photo::class, mappedBy="game", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity=SaveGame::class, mappedBy="game")
     */
    private $saveGames;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="game", fetch="EXTRA_LAZY",)
     * @ORM\JoinTable(name="games_tags")
     *
     * @Assert\Type(type="Doctrine\Common\Collections\Collection")
     */
    private $tags;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mingamers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxgamers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ispolish;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="games")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=FavoriteGames::class, mappedBy="game")
     */
    private $favoriteGames;

    public function __construct()
    {
        $this->saveGames = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->favoriteGames = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for ReleaseDate.
     *
     * @return string | array RelaseDate
     */
    public function getReleaseDate(): ?string
    {
        return $this->releasedate;
    }

    /**
     * Setter for ReleaseDate.
     *
     * @return $this
     */
    public function setReleaseDate(string $releasedate): self
    {
        $this->releasedate = $releasedate;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }


    public function getExtensive(): ?int
    {
        return $this->extensive;
    }

    public function setExtensive(int $extensive): self
    {
        $this->extensive = $extensive;

        return $this;
    }

    public function getGametime(): ?int
    {
        return $this->gametime;
    }

    public function setGametime(?int $gametime): self
    {
        $this->gametime = $gametime;

        return $this;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): self
    {
        // unset the owning side of the relation if necessary
        if ($photo === null && $this->photo !== null) {
            $this->photo->setGame(null);
        }

        // set the owning side of the relation if necessary
        if ($photo !== null && $photo->getGame() !== $this) {
            $photo->setGame($this);
        }

        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, SaveGame>
     */
    public function getSaveGames(): Collection
    {
        return $this->saveGames;
    }

    public function addSaveGame(SaveGame $saveGame): self
    {
        if (!$this->saveGames->contains($saveGame)) {
            $this->saveGames[] = $saveGame;
            $saveGame->addGame($this);
        }

        return $this;
    }

    public function removeSaveGame(SaveGame $saveGame): self
    {
        if ($this->saveGames->removeElement($saveGame)) {
            $saveGame->removeGame($this);
        }

        return $this;
    }

    /**
     * Getter for tags.
     *
     * @return Collection|Tag[] Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to collection.
     *
     * @param Tag $tag Tag entity
     *
     * @return Game
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Remove tag from collection.
     *
     * @param Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    public function getMingamers(): ?int
    {
        return $this->mingamers;
    }

    public function setMingamers(?int $mingamers): self
    {
        $this->mingamers = $mingamers;

        return $this;
    }

    public function getMaxgamers(): ?int
    {
        return $this->maxgamers;
    }

    public function setMaxgamers(?int $maxgamers): self
    {
        $this->maxgamers = $maxgamers;

        return $this;
    }

    public function getIspolish(): ?int
    {
        return $this->ispolish;
    }

    public function setIspolish(?int $ispolish): self
    {
        $this->ispolish = $ispolish;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setGames($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getGames() === $this) {
                $comment->setGames(null);
            }
        }

        return $this;
    }
    public function getFavoriteGames(): Collection
    {
        return $this->favoriteGames;
    }

    /**
     * @return $this
     */
    public function addFavoriteGame(FavoriteGames $favoriteMovie): self
    {
        if (!$this->favoriteMovies->contains($favoriteMovie)) {
            $this->favoriteMovies[] = $favoriteMovie;
            $favoriteMovie->addIdGame($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeFavoriteMovie(FavoriteGames $favoriteMovie): self
    {
        if ($this->favoriteMovies->removeElement($favoriteMovie)) {
            $favoriteMovie->removeIdGame($this);
        }

        return $this;
    }
}
