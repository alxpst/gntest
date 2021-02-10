<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function showAllBooks()
    {
        return $this->findAll();
    }

    public function searchByName($value)
    {
        $qb = $this->createQueryBuilder('b');
        $value = $qb->expr()->literal("%{$value}%");

        return $qb->select('b.id, b.Name, a.Name AS Author')
            ->join('b.Author', 'a', 'WITH', 'b.Author = a.id', 'a.id')
            ->andWhere($qb->expr()->like('b.Name', $value))
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
