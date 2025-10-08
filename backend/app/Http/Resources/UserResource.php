<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nombre_completo' => "{$this->nombres} {$this->apellidos}",
            'email' => $this->email,
            'telefono' => $this->telefono,
            'estado' => $this->estado,
            'fecha_registro' => $this->fecha_registro?->format('Y-m-d H:i:s'),
            'fecha_ultima_modificacion' => $this->fecha_ultima_modificacion?->format('Y-m-d H:i:s'),
        ];
    }
}
