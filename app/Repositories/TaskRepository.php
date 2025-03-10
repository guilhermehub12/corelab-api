<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @var Task
     */
    protected Task $task;
    
    /**
     * TaskRepository constructor
     * 
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
    
    /**
     * Obtém todas as tarefas de um usuário com paginação
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Cache::remember("user.{$userId}.tasks.page." . request('page', 1), 60, function () use ($userId, $perPage) {
            return $this->task
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        });
    }
    
    /**
     * Encontra uma tarefa pelo ID
     *
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id): ?Task
    {
        return Cache::remember("task.{$id}", 60, function () use ($id) {
            return $this->task->find($id);
        });
    }
    
    /**
     * Cria uma nova tarefa
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        $task = $this->task->create($data);
        
        // Clear user tasks cache
        $this->clearUserTasksCache($data['user_id']);
        
        return $task;
    }
    
    /**
     * Atualiza uma tarefa existente
     *
     * @param int $id
     * @param array $data
     * @return Task|null
     */
    public function update(int $id, array $data): ?Task
    {
        $task = $this->findById($id);
        
        if (!$task) {
            return null;
        }
        
        $task->update($data);
        
        // Limpa caches
        Cache::forget("task.{$id}");
        $this->clearUserTasksCache($task->user_id);
        
        return $task->fresh();
    }
    
    /**
     * Deleta uma tarefa existente
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $task = $this->findById($id);
        
        if (!$task) {
            return false;
        }
        
        $userId = $task->user_id;
        $result = $task->delete();
        
        // Limpa caches
        Cache::forget("task.{$id}");
        $this->clearUserTasksCache($userId);
        
        return $result;
    }
    
    /**
     * Encontra tarefas por status para um usuário
     *
     * @param int $userId
     * @param string $status
     * @return Collection
     */
    public function findByStatus(int $userId, string $status): Collection
    {
        return Cache::remember("user.{$userId}.tasks.status.{$status}", 60, function () use ($userId, $status) {
            return $this->task
                ->where('user_id', $userId)
                ->where('status', $status)
                ->orderBy('due_date', 'asc')
                ->get();
        });
    }
    
    /**
     * Limpa caches da tarefa de um usuário
     *
     * @param int $userId
     * @return void
     */
    private function clearUserTasksCache(int $userId): void
    {
        Cache::forget("user.{$userId}.tasks.page." . request('page', 1));
        
        // Limpa status caches
        $statuses = ['pending', 'in_progress', 'completed'];
        foreach ($statuses as $status) {
            Cache::forget("user.{$userId}.tasks.status.{$status}");
        }
    }
}