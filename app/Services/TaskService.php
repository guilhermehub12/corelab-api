<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskService
{
    protected TaskRepositoryInterface $taskRepository;
    
    /**
     * TaskService constructor
     * 
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    
    /**
     * Obtém todas as tarefas para o usuário autenticado
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllTasks(int $perPage = 15): LengthAwarePaginator
    {
        return $this->taskRepository->getAllForUser(Auth::id(), $perPage);
    }
    
    /**
     * Obtém uma tarefa por id
     *
     * @param int $id
     * @return Task
     * @throws AuthorizationException
     */
    public function getTask(int $id): Task
    {
        $task = $this->taskRepository->findById($id);
        
        if (!$task) {
            abort(404, 'Tarefa não encontrada');
        }
        
        if (Gate::denies('view', $task)) {
            throw new AuthorizationException('Você não está autorizado a ver esta tarefa');
        }
        
        return $task;
    }
    
    /**
     * Cria uma nova tarefa
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        $data['user_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 'pending';
        
        return $this->taskRepository->create($data);
    }
    
    /**
     * Atualiza uma tarefa existente
     *
     * @param int $id
     * @param array $data
     * @return Task
     * @throws AuthorizationException
     */
    public function updateTask(int $id, array $data): Task
    {
        $task = $this->taskRepository->findById($id);
        
        if (!$task) {
            abort(404, 'Tarefa não encontrada');
        }
        
        if (Gate::denies('update', $task)) {
            throw new AuthorizationException('Você não está autorizado a atualizar esta tarefa');
        }
        
        unset($data['user_id']);
        
        $updatedTask = $this->taskRepository->update($id, $data);
        
        if (!$updatedTask) {
            abort(500, 'Falha ao atualizar a tarefa');
        }
        
        return $updatedTask;
    }
    
    /**
     * Deleta uma tarefa
     *
     * @param int $id
     * @return bool
     * @throws AuthorizationException
     */
    public function deleteTask(int $id): bool
    {
        $task = $this->taskRepository->findById($id);
        
        if (!$task) {
            abort(404, 'Tarefa não encontrada');
        }
        
        if (Gate::denies('delete', $task)) {
            throw new AuthorizationException('Você não está autorizado a excluir esta tarefa');
        }
        
        return $this->taskRepository->delete($id);
    }
    
    /**
     * Obtém tarefas por status
     *
     * @param string $status
     * @return Collection
     */
    public function getTasksByStatus(string $status): Collection
    {
        return $this->taskRepository->findByStatus(Auth::id(), $status);
    }
}