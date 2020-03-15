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
    * @ORM\Column(type="string", nullable=true)
    */
    private $keywords;

    /**
     * @ORM\Column(type="integer")
     */
    protected $img_w;

    /**
     * @ORM\Column(type="integer")
     */
    protected $img_h;

    /**
     * @ORM\Column(type="integer")
     */
    protected $red_w;

    /**
     * @ORM\Column(type="integer")
     */
    protected $red_h;

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
     * @return array
     */
    public function getLocaleTitles(): ?array
    {
        if(!is_array(json_decode($this->title, true))) {
            return ['fr' => '?', 'en' => '?'];
        }

        $localeTitles = json_decode($this->title, true);

        return $localeTitles;
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
     * @return string
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return integer
     */
    public function getImg_W(): ?string
    {
        return $this->img_w;
    }

    /**
     * @param integer $img_w
     */
    public function setImg_W(string $img_w): self
    {
        $this->img_w = $img_w;

        return $this;
    }

    /**
     * @return integer
     */
    public function getImg_H(): ?string
    {
        return $this->img_h;
    }

    /**
     * @param integer $img_h
     */
    public function setImg_H(string $img_h): self
    {
        $this->img_h = $img_h;

        return $this;
    }

    /**
     * @return integer
     */
    public function getRed_W(): ?string
    {
        return $this->red_w;
    }

    /**
     * @param integer $red_w
     */
    public function setRed_W(string $red_w): self
    {
        $this->red_w = $red_w;

        return $this;
    }

    /**
     * @return integer
     */
    public function getRed_H(): ?string
    {
        return $this->red_h;
    }

    /**
     * @param integer $red_h
     */
    public function setRed_H(string $red_h): self
    {
        $this->red_h = $red_h;

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
