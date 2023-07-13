<?php
namespace App\Repository\Second;

use App\Entity\Second\Todolist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Todolist>
 *
 * @method Todolist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todolist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todolist[]    findAll()
 * @method Todolist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodolistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todolist::class);
    }
}
