<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Packs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @method Packs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Packs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Packs[]    findAll()
 * @method Packs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Packs::class);
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Packs $entity, bool $flush = true): void
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
    public function remove(Packs $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }



    public function findSearch(SearchData $searchData): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('p');

        if (!empty($searchData->searchValue)) {
            $query = $query->andWhere('p.nom like :s')->setParameter('s', "%{$searchData->searchValue}%");
        }
        if (!empty($searchData->min)) {
            $query = $query->andWhere('p.prix >= :min')->setParameter('min', $searchData->min);
        }
        if (!empty($searchData->max)) {
            $query = $query->andWhere('p.prix  <= :max')->setParameter('max', $searchData->max);
        }

        if (!empty($searchData->promo)) {
            $query = $query->andWhere('p.promo > 0');
        }

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $searchData->page,
            6
        );
    }

    public function getPackUser($id)
    {

        return $this->createQueryBuilder('p')
            ->join('App\Entity\Utilisateur', 'u')
            ->where('p.iduser=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Packs[] Returns an array of Packs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Packs
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
