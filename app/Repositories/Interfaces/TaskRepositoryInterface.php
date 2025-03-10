<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    /**
     * Obtém todas as tarefas de um usuário com paginação
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllForUser(int $userId, int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Encontra uma tarefa pelo ID
     *
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id): ?Task;
    
    /**
     * Cria uma nova tarefa
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task;
    
    /**
     * Atualiza uma tarefa existente
     *
     * @param int $id
     * @param array $data
     * @return Task|null
     */
    public function update(int $id, array $data): ?Task;
    
    /**
     * Deleta uma tarefa existente
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
    
    /**
     * Encontra tarefas por status para um usuário
     *
     * @param int $userId
     * @param string $status
     * @return Collection
     */
    public function findByStatus(int $userId, string $status): Collection;
}