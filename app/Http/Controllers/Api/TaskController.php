<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="Endpoints para Gestão das Tarefas"
 * )
 */
class TaskController extends Controller
{
    protected TaskService $taskService;
    
    /**
     * TaskController constructor
     * 
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    
    /**
     * Lista todas as tarefas.
     * 
     * @OA\Get(
     *     path="/api/tasks",
     *     operationId="getTasks",
     *     tags={"Tasks"},
     *     summary="Obter todas as tarefas do usuário autenticado",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar tarefas por status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "in_progress", "completed"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listagem de tarefas bem-sucedida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Task")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        if (request()->has('status')) {
            $tasks = $this->taskService->getTasksByStatus(request('status'));
            return TaskResource::collection($tasks);
        }
        
        $tasks = $this->taskService->getAllTasks();
        return TaskResource::collection($tasks);
    }
    
    /**
     * Armarzena uma nova tarefa.
     * 
     * @OA\Post(
     *     path="/api/tasks",
     *     operationId="storeTask",
     *     tags={"Tasks"},
     *     summary="Cria uma nova tarefa",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Task"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());
        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }
    
    /**
     * Exibe uma tarefa específica.
     * 
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     operationId="getTask",
     *     tags={"Tasks"},
     *     summary="Obtém uma tarefa específica",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa listada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Task"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=403, description="Acesso proibido"),
     *     @OA\Response(response=404, description="Tarefa não encontrada")
     * )
     */
    public function show(int $id): TaskResource
    {
        $task = $this->taskService->getTask($id);
        return new TaskResource($task);
    }
    
    /**
     * Atualiza uma tarefa específica.
     * 
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     operationId="updateTask",
     *     tags={"Tasks"},
     *     summary="Atualiza uma tarefa específica",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da Tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada com sucesso!",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Task"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=403, description="Acesso proibido"),
     *     @OA\Response(response=404, description="Tarefa não encontrada"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function update(UpdateTaskRequest $request, int $id): TaskResource
    {
        $task = $this->taskService->updateTask($id, $request->validated());
        return new TaskResource($task);
    }
    
    /**
     * Remove a tarefa específica.
     * 
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     operationId="deleteTask",
     *     tags={"Tasks"},
     *     summary="Remove a tarefa específica",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da Tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Tarefa excluída com sucesso"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=403, description="Acesso proibido"),
     *     @OA\Response(response=404, description="Tarefa não encontrada")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->taskService->deleteTask($id);
        return response()->json(null, 204);
    }
}