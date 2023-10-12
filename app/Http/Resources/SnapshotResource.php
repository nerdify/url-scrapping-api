<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property string html
 * @property int status_code
 * @property string created_at
 * @property string updated_at
 * @property string scan_interval
 * @property string last_scanned_at
 */
class SnapshotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'site' => SiteResource::make($this->whenLoaded('site')),

            'html' => $this->html,
            'status_code' => $this->status_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'scan_interval' => $this->scan_interval,
            'last_scanned_at' => $this->last_scanned_at,
        ];
    }
}
