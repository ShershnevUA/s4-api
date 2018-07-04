<?php

namespace App\Repository;

use App\Entity\Chanel;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function searchInChanel( string $query, Chanel $chanel )
    {
        $qb = $this->createQueryBuilder('u')
            ->innerJoin('u.memberInChannels', 'ch')
            ->where('LOWER(u.username) LIKE LOWER(:q)')
            ->orWhere('LOWER(u.email) LIKE LOWER(:q)')
            ->andWhere( 'ch = :chanel' )
            ->setMaxResults(50)
            ->setParameters([
                'q'     => $query . '%',
                'chanel'    => $chanel
            ]);

        return $qb->getQuery()->getResult();
    }
}
