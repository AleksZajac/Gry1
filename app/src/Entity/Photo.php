<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Photo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(
 *     name="photos",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="UQ_filename_1",
 *              columns={"filename"},
 *          ),
 *     },
 * )
 *
 * @UniqueEntity(
 *      fields={"filename"},
 * )
 */
class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \App\Entity\Game
     * @ORM\OneToOne(targetEntity=Game::class, inversedBy="photo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     *  @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=191,
     * )
     *
     * @Assert\Type(type="string")
     */
    private $filename;

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for user.
     *
     * @return \App\Entity\Game|null Game
     */
    public function getGame(): ?Game
    {
        return $this->game;
    }
    /**
     * Setter for game.
     *
     * @param \App\Entity\Game $game Game
     *
     * @return Photo
     */
    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
    /**
     * Getter for filename.
     *
     * @return string Filename
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }
    /**
     * Setter for filename.
     *
     * @param string $filename Filename
     *
     * @return Photo
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
