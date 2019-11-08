<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function existsUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function existsEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function isAlreadyUsedUsername($username, $user_id)
    {
        return $this->createQueryBuilder('u')
                    ->where("u.username = ?1")
                    ->andWhere("u.id != ?2")
                    ->setParameter(1, $username)
                    ->setParameter(2, $user_id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function isAlreadyUsedEmail($email, $user_id)
    {
        return $this->createQueryBuilder('u')
                    ->where("u.email = ?1")
                    ->andWhere("u.id != ?2")
                    ->setParameter(1, $email)
                    ->setParameter(2, $user_id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
