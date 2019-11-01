<?php
// src/Repository/PlayerRepository.php
namespace App\Repository;

use App\Entity\Player;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findAllPlayers()
    {
        return $this->findAll([], ['date' => 'DESC']);
    }

    public function existsPlayer($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}