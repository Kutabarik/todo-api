<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getAll(): array
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(array $filters)
    {
        $qb = $this->createQueryBuilder('t');

        if (isset($filters['completed'])) {
            $qb->andWhere('t.isCompleted = :completed')
                ->setParameter('completed', filter_var($filters['completed'], FILTER_VALIDATE_BOOLEAN));
        }

        if (isset($filters['priority'])) {
            $qb->andWhere('t.priority = :priority')
                ->setParameter('priority', $filters['priority']);
        }

        return $qb->getQuery()->getResult();
    }
}
