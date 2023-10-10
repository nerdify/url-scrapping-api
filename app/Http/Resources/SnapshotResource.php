<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property string created_at
 * @property string updated_at
 * @property string scan_interval
 * @property string last_scanned_at
 */
class SnapshotResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [];
    }
}
