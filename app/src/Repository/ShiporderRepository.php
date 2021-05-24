<?php

namespace App\Repository;

use App\Entity\Shiporder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shiporder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shiporder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shiporder[]    findAll()
 * @method Shiporder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiporderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shiporder::class);
    }
}
