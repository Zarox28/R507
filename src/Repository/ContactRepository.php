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

    /**
     * @return Contact[] Returns an array of Contact objects with pagination and status filter
     */
    public function paginateByStatus(string $status, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder("c")
            ->andWhere("c.status = :status")
            ->setParameter("status", $status)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Count contacts by status
     */
    public function countByStatus(string $status): int
    {
        return $this->createQueryBuilder("c")
            ->select("count(c.id)")
            ->andWhere("c.status = :status")
            ->setParameter("status", $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Contact[] Returns an array of Contact objects with pagination, search and status filter
     */
    public function searchWithPaginationAndStatus(string $search, string $status, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        $qb = $this->createQueryBuilder("c");

        return $qb
            ->andWhere(
                $qb->expr()->orX($qb->expr()->like("c.firstName", ":search"), $qb->expr()->like("c.name", ":search")),
            )
            ->andWhere("c.status = :status")
            ->setParameter("search", "%" . $search . "%")
            ->setParameter("status", $status)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Count search results with status filter
     */
    public function countSearchWithStatus(string $search, string $status): int
    {
        $qb = $this->createQueryBuilder("c");

        return $qb
            ->select("count(c.id)")
            ->andWhere(
                $qb->expr()->orX($qb->expr()->like("c.firstName", ":search"), $qb->expr()->like("c.name", ":search")),
            )
            ->andWhere("c.status = :status")
            ->setParameter("search", "%" . $search . "%")
            ->setParameter("status", $status)
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
