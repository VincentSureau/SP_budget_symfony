<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /*
     * Get Categories with current user operations
     * @return Category[] Returns an array of Category objects
     */
    public function getCategoriesWithOperations(User $user, \DateTime $dateStart, \DateTime $dateEnd)
    {
        return $this->createQueryBuilder('c')
            ->addSelect('o')
            ->leftJoin('c.operations', 'o')
            ->andWhere('o.user = :user')
            ->setParameter('user', $user)
            ->andWhere('o.date >= :dateStart')
            ->setParameter(':dateStart', $dateStart)
            ->andWhere('o.date <= :dateEnd')
            ->setParameter(':dateEnd', $dateEnd)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getStats(User $user)
    {
        $sql = "
            SELECT
                c.name
                ,YEAR(o.date) as year
                ,MONTH(o.date) as month
            FROM category as c
            INNER JOIN operation as o ON c.id = o.category_id
            GROUP BY c.id, year, month
            ORDER BY year DESC, month DESC
        ";

        $pdo = $this
            ->getEntityManager()
            ->getConnection()
        ;

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        return $data;
    }
}
