<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'is_overdue' => $this->isOverdue(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * Get human-readable status label
     *
     * @return string
     */
    private function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            default => ucfirst($this->status),
        };
    }
    
    /**
     * Determine if the task is overdue
     *
     * @return bool
     */
    private function isOverdue(): bool
    {
        if (!$this->due_date) return false;
        
        return $this->due_date->isPast() && $this->status !== 'completed';
    }
}