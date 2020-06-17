<?php

namespace App\Repository;

use PDO;
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

    // note: on pourrait refactoriser la fonction en DQL
    public function getStats(User $user)
    {
        $sql = "
            SELECT
                c1.name
                ,YEAR(o2.date) as year
                ,MONTH(o2.date) as month
                ,(
                    SELECT sum
                    FROM
                    (
                        SELECT 
                            DISTINCT(c2.id),
                            SUM(CASE
                                WHEN o2.type = 'expense'
                                    THEN (o2.amount * -1)
                                ELSE o2.amount
                            END) as sum
                        FROM category as c2
                        INNER JOIN operation as o2 ON c2.id = o2.category_id
                        WHERE o2.category_id = c1.id
                        AND o2.user_id = :user_id
                        AND month(o2.date) = month
                        GROUP BY o2.category_id
                    ) as sum
                ) as amount
            FROM category as c1
            INNER JOIN operation as o2 ON c1.id = o2.category_id
            WHERE o2.user_id = :user_id
            AND o2.date >= :date
            GROUP BY c1.id, year, month
            ORDER BY year ASC, month ASC
        ";

        $pdo = $this
            ->getEntityManager()
            ->getConnection()
        ;

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('user_id', $user->getId());
        $date = new \DateTime('first day of last year + 1 month');
        $stmt->bindValue('date', $date->format('Y-m-d'));
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}
