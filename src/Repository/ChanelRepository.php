<?php

namespace App\Repository;

use App\Entity\Chanel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Chanel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chanel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chanel[]    findAll()
 * @method Chanel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChanelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Chanel::class);
    }

    public function getChannels($query)
    {
        $qb = $this->createQueryBuilder('ch')
            ->where('LOWER(ch.title) LIKE LOWER(:q)')
            ->andWhere( 'ch.private = false' )
            ->setMaxResults(50)
            ->setParameters([
                'q'     => $query . '%',
            ]);

        return $qb->getQuery()->getResult();
    }

    public function getMyChannels($user){
        $qb = $this->createQueryBuilder('ch')
            ->innerJoin('ch.members', 'm')
            ->where('ch.owner = :user')
            ->orWhere('m = :user')
            ->setParameters([
                'user'     => $user,
            ]);

        return $qb->getQuery()->getResult();
    }
}
