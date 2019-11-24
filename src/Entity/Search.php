<?php
// src/Entity/Search.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Search
{
    /**
    * @ORM\Column(type="string")
    */
    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }
}
