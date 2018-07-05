<?php

namespace App\Repository;

use App\Entity\Chanel;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getChanelMessages(Chanel $chanel)
    {
        $qb = $this->createQueryBuilder('m')
            ->innerJoin('m.chanel', 'ch')
            ->where('ch = :chanel')
            ->setParameter('chanel', $chanel);

        return $qb->getQuery()->getResult();
    }
}
