<?php

namespace App\Http\Requests\Task;

/**
 * @OA\Schema(
 *     schema="StoreTaskRequest",
 *     required={"title"},
 *     @OA\Property(property="title", type="string", example="Concluir a documentação do projeto"),
 *     @OA\Property(property="description", type="string", example="Escrever documentação abrangente para o projeto"),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="pending"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2023-12-31")
 * )
 */
class StoreTaskRequest extends BaseTaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge($this->commonRules(), [
            'title' => 'required|string|min:3|max:255',
        ]);
    }
}
