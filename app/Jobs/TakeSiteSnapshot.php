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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
        logger('TakeSiteSnapshot job started');

        try {
            DB::beginTransaction();

            $response = Http::get($this->site->url);

            logger($response->status());

            $this->site->update([
                'last_scanned_at' => now()->toDateTime(),
            ]);

            /** @var Snapshot $snapshot */
            $snapshot = $this->site->snapshots()->create([
                'status_code' => $response->status(),
            ]);

            if ($response->successful()) {

                if (!Storage::disk('public')->directoryExists("snapshots/site-{$this->site->id}")) {
                    Storage::disk('public')->makeDirectory("snapshots/site-{$this->site->id}");
                }

                $filename = "{$this->site->id}-".now()->format('Ymd-His').".png";
                $pathToSnapshot = Storage::disk('public')->path("snapshots/site-{$this->site->id}");

                logger($pathToSnapshot);

                $browserShot = Browsershot::url($this->site->url)
                    ->newHeadless()
                    ->setChromePath('/usr/bin/google-chrome'));

                $snapshot->update([
                    'html' => $browserShot->bodyHtml(),
                ]);

                $browserShot->setScreenshotType('png', 100)
                    ->save($pathToSnapshot.'/'.$filename);

                $snapshot->addMedia($pathToSnapshot.'/'.$filename)
                    ->withResponsiveImages()
                    ->toMediaCollection();
            }

            DB::commit();

//            $this->release($this->site->scan_interval * 60);

            TakeSiteSnapshot::dispatch($this->site->fresh())
                ->onQueue('snapshots')
                ->delay(now()->addMinutes($this->site->scan_interval));

            logger('TakeSiteSnapshot job finished, next job will be released in ' . $this->site->scan_interval * 60 . ' seconds');

        } catch (Throwable $e) {
            logger($e->getMessage());
            DB::rollBack();

//            $this->release($this->site->scan_interval * 60);

            TakeSiteSnapshot::dispatch($this->site->fresh())
                ->onQueue('snapshots')
                ->delay(now()->addMinutes($this->site->scan_interval));

            logger('TakeSiteSnapshot job failed, next job will be released in ' . $this->site->scan_interval * 60 . ' seconds');
        }

    }
}
