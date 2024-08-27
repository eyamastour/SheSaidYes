<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Utilisateur $entity, bool $flush = true): void
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
    public function remove(Utilisateur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


        /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return Utilisateur[] Returns an array of Utilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByEmail(String $value, String $v,String $va)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :val')
            ->andWhere('u.password = :vall')
            ->andWhere('u.role = :va')
            ->setParameter('val', $value)
            ->setParameter('vall', $v)
            ->setParameter('va', $va)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }


    public function findByEmailA(String $value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }

    public function verifier($email, $mdp)
    {
        {
            $em = $this->getEntityManager();

            $query = $em->createQuery(
                'SELECT u FROM   App\Entity\Users u  where   u.password = :pass   and  u.email = :email '
            );
            $query->setParameter('email', $email);
            $query->setParameter('password', $mdp);


            return $query->getResult();
        }
    }
    public function verifieremail($email)
    {
        {
            $em = $this->getEntityManager();

            $query = $em->createQuery(
                'SELECT u FROM   App\Entity\Utilisateur u  where   u.email = :email '
            );
            $query->setParameter('email', $email);


            return $query->getResult();
        }
    }
    public function verifierid($id)
    {
        {
            $em = $this->getEntityManager();

            $query = $em->createQuery(
                'SELECT u FROM   App\Entity\Utilisateur u  where   u.id = :id '
            );
            $query->setParameter('id', $id);


            return $query->getResult();
        }
    }
    
}
