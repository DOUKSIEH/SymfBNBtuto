<?php

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

     /**
     * @return Ad[] Returns an array of Ad objects
    */
   
    public function findAllAds($term)
    {
        return $this->createQueryBuilder('a')
           // ->andWhere('a.exampleField = :val')
          //  ->setParameter('val', $value)
            ->andWhere('a.slug LIKE :searchTerm OR a.title LIKE :searchTerm')
            ->setParameter('searchTerm', $term.'%')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }
     /**
     * @return Ad[] Returns an array of Ad objects
    */
    public function findAllAD(): array
    {
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
        SELECT * 
        FROM Ad p
        JOIN User as u
        ON  u.id = p.author_id
        ORDER BY p.id DESC
        LIMIT 6
        ';
    $stmt = $conn->prepare($sql);
   $stmt->execute();

    // returns an array of arrays (i.e. a raw data set)
    return $stmt->fetchAll();
    }
   

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
