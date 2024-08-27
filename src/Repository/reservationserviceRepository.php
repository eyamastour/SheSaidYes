<?php

namespace App\Repository;

use App\Entity\PropertySearch1;
use App\Entity\Reservationservice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservationservice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservationservice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservationservice[]    findAll()
 * @method Reservationservice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class reservationserviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservationservice::class);
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservationservice $entity, bool $flush = true): void
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
    public function remove(Reservationservice $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Reservationservice[] Returns an array of Reservationservice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservationservice
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countres(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        select count(*)
        from reservationservice;
        ";
    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();

    return $resultSet->fetchAllAssociative();
      

    }

    public function findByDate($txt)
    {
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT p from App\Entity\Reservationservice p where p.prixresserv like :txt")
            ->setParameter('txt','%'.$txt.'%');
        return $query->getResult();
    }

    public function findByUser($iduser){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        select *
        from reservationservice  where  iduser = $iduser;
        ";
    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();
 
    return $resultSet->fetchAllAssociative();
    }
}
