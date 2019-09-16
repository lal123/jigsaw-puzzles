<?php
// src/Entity/Article.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(name="blog_article")
 */
class Article
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
    * @ORM\Column(type="text")
    */
    private $content;

    /**
    * @ORM\Column(type="string")
    */
    private $author;

    /**
    * @ORM\Column(name="date", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
    */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime(); 
    }
    /**
     * @ORM\PrePersist
     */
    public function onPrePersistSetRegistrationDate()
    {
        $this->date = new \DateTime();
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
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }
    public function getDate(): ?datetime
    {
        return $this->date;
    }

    public function setDate(datetime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
