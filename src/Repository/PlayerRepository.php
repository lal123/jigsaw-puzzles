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

    public function existsPlayer($name, $excluded_id = null)
    {
        //return $this->findOneBy(['name' => $name]);
        $player = $this->createQueryBuilder('u')
                    ->where("u.name = ?1")
                    ->setParameter(1, $name);
        if(null !== $excluded_id) {
                    $player = $player->andWhere("u.id != ?2")
                    ->setParameter(2, $excluded_id);
        }
        $player = $player->getQuery();
        $player = $player->getResult();
        return $player;
    }
}