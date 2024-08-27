<?php

namespace App\Repository;

use App\Entity\Espaceprestatairee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Espaceprestatairee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Espaceprestatairee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Espaceprestatairee[]    findAll()
 * @method Espaceprestatairee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class espaceprestataireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Espaceprestatairee::class);
    }
    
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Espaceprestatairee $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Espaceprestatairee $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Espaceprestatairee[] Returns an array of Espaceprestatairee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Espaceprestatairee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}