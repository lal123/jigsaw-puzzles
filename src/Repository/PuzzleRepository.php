<?php

namespace App\Repository;

use App\Entity\Puzzle;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PuzzleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Puzzle::class);
    }

    public function findAllPuzzles()
    {
        return $this->findAll([], ['date' => 'DESC']);
    }
}