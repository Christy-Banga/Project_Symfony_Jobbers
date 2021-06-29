<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Recherche les annonces en fonction du formulaire
     */
    public function search($mots = null, $category = null){
        $query = $this->createQueryBuilder('s');
        $query->where('s.active = 1');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(s.title, s.content) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($category != null){
            $query->leftJoin('s.category', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $category);
        }
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Service[] Returns an array of Service objects
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
    public function findOneBySomeField($value): ?Service
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
