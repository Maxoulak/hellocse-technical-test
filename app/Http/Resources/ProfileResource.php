<?php

namespace App\Http\Resources;

use App\Models\Admin;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'admin_id' => $this->admin_id,
            'image' => $this->image === null ? null : (new MediaService())->publicUrl($this->image),
            'status' => $this->when($request->user() instanceof Admin, $this->status->value),
            'created_at' => $this->created_at->toAtomString(),
            'updated_at' => $this->updated_at->toAtomString(),
        ];
    }
}
