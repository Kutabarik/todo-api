<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tasks', name: 'api_tasks_')]
class TaskController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepository,
        private TaskService $taskService
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'], format: 'json')]
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'completed' => $request->query->get('completed'),
            'priority' => $request->query->get('priority'),
        ];

        $tasks = $this->taskRepository->findByFilters($filters);

        return $this->json($tasks, 200);
    }

    #[Route('', name: 'create', methods: ['POST'], format: 'json')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $task = $this->taskService->createTask($data);
            return $this->json($task, 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], format: 'json')]
    public function show(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        return $this->json($task);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'], format: 'json')]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        try {
            $updatedTask = $this->taskService->updateTask($task, $data);
            return $this->json($updatedTask);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}/complete', name: 'complete', methods: ['PATCH'], format: 'json')]
    public function markAsCompleted(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $updatedTask = $this->taskService->markAsCompleted($task);
        return $this->json($updatedTask);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], format: 'json')]
    public function delete(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $this->taskService->deleteTask($task);
        return $this->json(['status' => 'Task deleted'], 204);
    }
}
