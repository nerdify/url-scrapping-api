<?php

namespace App\Jobs;

use App\Models\Site;
use App\Models\Snapshot;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
use Throwable;

class TakeSiteSnapshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Site $site)
    {
        //
    }


    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $response = Http::get($this->site->url);

            $snapshotDate = now()->format('Ymd-His');

            $this->site->update([
                'last_scanned_at' => $snapshotDate,
            ]);

            /** @var Snapshot $snapshot */
            $snapshot = $this->site->snapshots()->create([
                'status_code' => $response->status(),
            ]);

            if ($response->successful()) {
                $pathToSnapshot = storage_path("app/public/snapshots/{$this->site->id}-{$snapshotDate}.png");

                $browserShot = Browsershot::url($this->site->url);

                $snapshot->update([
                    'html' => $browserShot->bodyHtml(),
                ]);

                $browserShot->setScreenshotType('png', 100)
                    ->save($pathToSnapshot);

                $snapshot->addMedia($pathToSnapshot)
                    ->withResponsiveImages()
                    ->toMediaCollection();
            }

            DB::commit();
        } catch (Throwable $e) {
            logger($e->getMessage());

            DB::rollBack();
        }

    }
}
