<?php
// src/Entity/Puzzle.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PuzzleRepository")
 * @ORM\Table(name="puzzles")
 */
class Puzzle
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string")
    */
    private $title;

    /**
    * @ORM\Column(type="string", length=1)
    */
    private $partner;

    /**
    * @ORM\Column(type="string", length=2)
    */
    private $locale;

    /**
    * @ORM\Column(type="string")
    */
    private $filename;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $published;

    public function __construct()
    {

    }

    /**
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getLocaleTitle($locale): ?string
    {
        return json_decode($this->title)->{$locale};
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getPartner(): ?string
    {
        return $this->partner;
    }

    /**
     * @param string $partner
     */
    public function setPartner(string $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return datetime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     */
    public function setCreated(?\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return datetime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @param datetime $updated
     */
    public function setUpdated(?\DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return datetime
     */
    public function getPublished(): \DateTime
    {
        return $this->published;
    }

    /**
     * @param datetime $published
     */
    public function setPublished(?\DateTime $published): void
    {
        $this->published = $published;
    }
}
