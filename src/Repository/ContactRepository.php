<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function paginate(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder("c")->setFirstResult($offset)->setMaxResults($limit)->getQuery()->getResult();
    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function search(string $search): array
    {
        $qb = $this->createQueryBuilder("c");
        return $qb
            ->andWhere(
                $qb->expr()->orX($qb->expr()->like("c.firstName", ":search"), $qb->expr()->like("c.name", ":search")),
            )
            ->setParameter("search", "%" . $search . "%")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Contact[] Returns an array of Contact objects with pagination and search
     */
    public function searchWithPagination(string $search, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        $qb = $this->createQueryBuilder("c");

        return $qb
            ->andWhere(
                $qb->expr()->orX($qb->expr()->like("c.firstName", ":search"), $qb->expr()->like("c.name", ":search")),
            )
            ->setParameter("search", "%" . $search . "%")
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Count search results
     */
    public function countSearch(string $search): int
    {
        $qb = $this->createQueryBuilder("c");

        return $qb
            ->select("count(c.id)")
            ->andWhere(
                $qb->expr()->orX($qb->expr()->like("c.firstName", ":search"), $qb->expr()->like("c.name", ":search")),
            )
            ->setParameter("search", "%" . $search . "%")
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    public function findOneBySomeField($value): ?Contact
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
