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

    public function getPlayerFromId($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function existsPlayer($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function existsPlayerName($name, $excluded_id)
    {
        return $this->createQueryBuilder('u')
                    ->where("u.name = ?1")
                    ->andWhere("u.id != ?2")
                    ->setParameter(1, $name)
                    ->setParameter(2, $excluded_id)
                    ->getQuery()
                    ->getResult();
    }
}