<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tasks', name: 'api_tasks_')]
class TaskController extends AbstractController
{
    public function __construct(
        private TaskService $taskService
    ) {}

    #[Route('', name: 'list', methods: ['GET'], format: 'json')]
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'completed' => $request->query->get('completed'),
            'priority' => $request->query->get('priority'),
        ];

        $tasks = $this->taskService->findByFilters($filters);

        return $this->json($tasks, Response::HTTP_OK);
    }

    #[Route('', name: 'create', methods: ['POST'], format: 'json')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $task = $this->taskService->createTask($data);
            return $this->json($task, Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], format: 'json')]
    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            return $this->json($task);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'], format: 'json')]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $task = $this->taskService->getTaskById($id);
            $updatedTask = $this->taskService->updateTask($task, $data);
            return $this->json($updatedTask);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/complete', name: 'complete', methods: ['PATCH'], format: 'json')]
    public function markAsCompleted(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            $updatedTask = $this->taskService->markAsCompleted($task);
            return $this->json($updatedTask);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], format: 'json')]
    public function delete(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            $this->taskService->deleteTask($task);
            return $this->json(['status' => 'Task deleted'], Response::HTTP_NO_CONTENT);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
