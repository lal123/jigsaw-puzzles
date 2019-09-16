<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllArticles()
    {
        return $this->findAll([], ['date' => 'DESC']);
    }
}