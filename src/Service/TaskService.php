<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        private EntityManagerInterface $em,
        private TaskRepository $taskRepository,
    ) {
    }

    public function createTask(array $data): Task
    {
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setDescription($data['description'] ?? null);
        $task->setCompleted($data['completed'] ?? false);
        $task->setPriority($data['priority'] ?? 'low');

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setCompleted($data['completed'] ?? $task->isCompleted());
        $task->setPriority($data['priority'] ?? $task->getPriority());

        $this->em->flush();

        return $task;
    }

    public function markAsCompleted(Task $task): Task
    {
        if ($task->isCompleted()) {
            throw new \InvalidArgumentException("Task is already marked as completed");
        }

        $task->setCompleted(true);
        $this->em->flush();

        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }
}
