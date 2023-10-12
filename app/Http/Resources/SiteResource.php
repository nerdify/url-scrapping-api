<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property string name
 * @property string url
 * @property string created_at
 * @property string updated_at
 * @property string scan_interval
 * @property string last_scanned_at
 */
class SiteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'snapshots' => SnapshotResource::collection($this->whenLoaded('snapshots')),

            'name' => $this->name,
            'url' => $this->url,
            'scan_interval' => $this->scan_interval,
            'last_scanned_at' => $this->last_scanned_at,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
