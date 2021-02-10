<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function showAllAuthors()
    {
        return $this->findAll();
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function findByName($name)
    {
        $qb = $this->createQueryBuilder('a');
        return $qb->where('a.Name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getArrayResult();
    }
}
