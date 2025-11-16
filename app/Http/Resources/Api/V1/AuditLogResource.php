<?php

namespace App\Http\Resources\Api\V1;

use App\Models\User; // Importe
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * Transforma o recurso em um array.
     */
    public function toArray(Request $request): array
    {
        // Pega o nome do usuário que fez a ação
        $userName = $this->user_id ? User::find($this->user_id)->name : 'Sistema';

        return [
            'id' => $this->id,
            'user' => $userName, // "João Marketing"
            'action' => $this->event, // "created", "updated", "deleted"
            'model' => class_basename($this->auditable_type), // "News", "Course"
            'modelId' => $this->auditable_id, // "21"
            'timestamp' => $this->created_at->format('d/m/Y H:i:s'), // Data

            // Quais campos mudaram?
            'changes' => [
                'old' => $this->old_values,
                'new' => $this->new_values,
            ]
        ];
    }
}