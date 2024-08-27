<?php

namespace App\Repository;

use App\Data\SearchDataService;
use App\Entity\Services;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Services|null find($id, $lockMode = null, $lockVersion = null)
 * @method Services|null findOneBy(array $criteria, array $orderBy = null)
 * @method Services[]    findAll()
 * @method Services[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Services::class);
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Services $entity, bool $flush = true): void
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
    public function remove(Services $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getPackServices($id)
    {

        return $this->createQueryBuilder('s')
            ->join('App\Entity\Packs', 'p')
            ->where('s.idpack=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    public function findSearch(SearchDataService $searchData): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('p');

        if (!empty($searchData->searchValue)) {
            $query = $query->andWhere('p.nom like :s')->setParameter('s', "%{$searchData->searchValue}%");
        }
        if (!empty($searchData->categories)) {
            $query = $query->andWhere('p.categorie IN (:categories)')->setParameter('categories', $searchData->categories);
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

        return $this->createQueryBuilder('s')
            ->join('App\Entity\Utilisateur', 'u')
            ->where('s.iduser=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Services[] Returns an array of Services objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Services
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
