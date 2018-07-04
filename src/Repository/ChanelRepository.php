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

    public function getChenals($query)
    {
        $qb = $this->createQueryBuilder('ch')
            ->where('LOWER(ch.title) LIKE LOWER(:q)')
            ->andWhere( 'ch.private = true' )
            ->setMaxResults(50)
            ->setParameters([
                'q'     => $query . '%',
            ]);

        return $qb->getQuery()->getResult();
    }
//    /**
//     * @return Chanel[] Returns an array of Chanel objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chanel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
