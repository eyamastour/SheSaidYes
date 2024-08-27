<?php

namespace App\Repository;

use App\Entity\Reservationpack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservationpack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservationpack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservationpack[]    findAll()
 * @method Reservationpack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class reservationpackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservationpack::class);
    } 

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservationpack $entity, bool $flush = true): void
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
    public function remove(Reservationpack $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Reservationpack[] Returns an array of Reservationpack objects
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
    public function findOneBySomeField($value): ?Reservationpack
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function statsForeveryYear($annee) 
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        select date_format(date,'%M') as months,substr(date,1,4) as annees, sum(r.prixrespack) as somme 
        from reservationpack r 
        where SUBSTR(date,1,4) like :annee 
        group by months,annees
        ORDER BY months ASC;
        ";
    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery(['annee' => $annee]);

    return $resultSet->fetchAllAssociative();
    }

    

    public function statsForAllYears() 
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        select substr(date,1,4) as annees, date_format(date,'%M') as months, sum(r.prixrespack) as somme 
        from reservationpack r 
        group by months,annees 
        ORDER BY annees ASC, months ASC;
        ";
    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();

    return $resultSet->fetchAllAssociative();
    }


    public function allYears(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        select substr(date,1,4) as annees
        from reservationpack  
        group by annees
        ORDER BY annees;
        ";
    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();
 
    return $resultSet->fetchAllAssociative();
    }


    
    public function search($prixrespack)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.prixrespack LIKE :prixrespack')
            ->setParameter('prixrespack', '%'.$prixrespack.'%')
            ->getQuery()
            ->execute();
    }

    public function findByUser($iduser){
       
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        select *
        from reservationpack  where  iduser = $iduser;
        ";
    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();
 
    return $resultSet->fetchAllAssociative();

    }

  
}
