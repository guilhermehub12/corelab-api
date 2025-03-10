<?php

namespace App\Http\Requests\Task;

/**
 * @OA\Schema(
 *     schema="UpdateTaskRequest",
 *     @OA\Property(property="title", type="string", example="Documentação do projeto atualizado"),
 *     @OA\Property(property="description", type="string", example="Documentação abrangente atualizada para o projeto"),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="in_progress"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2023-12-31")
 * )
 */
class UpdateTaskRequest extends BaseTaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return $this->commonRules();
    }

    /**
     * Get the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.min' => 'O título da tarefa deve ter pelo menos :min caracteres.',
            'due_date.after_or_equal' => 'A data de vencimento deve ser hoje ou uma data futura.',
        ];
    }
}
