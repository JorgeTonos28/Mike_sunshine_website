<?php

namespace App\Jobs;

use App\Mail\StudioMessage;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\StudioSetting;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NotifyStudio implements ShouldBeEncrypted, ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public int $timeout = 30;

    public function __construct(public string $kind, public int $recordId) {}

    public function backoff(): array
    {
        return [60, 300, 900, 3600];
    }

    public function handle(): void
    {
        $record = $this->kind === 'order' ? Order::findOrFail($this->recordId) : Inquiry::findOrFail($this->recordId);
        if ($record->notification_status === 'sent') {
            return;
        }
        $recipient = StudioSetting::current()->contact_email ?: config('studio.email');
        if (! $recipient) {
            throw new \RuntimeException('Studio recipient is not configured.');
        }
        $url = $this->kind === 'order' ? url('/admin/orders/'.$record->id) : url('/admin/inquiries');
        // Keep customer PII out of messages/queue logs; the authorized panel contains the details.
        Mail::to($recipient)->send(new StudioMessage($this->kind === 'order' ? 'Nueva solicitud '.$record->reference : 'Nueva consulta del sitio', $url));
        $record->notification_status = 'sent';
        if ($record instanceof Order) {
            $record->notified_at = now();
        }
        $record->save();
    }

    public function failed(?\Throwable $exception): void
    {
        $class = $this->kind === 'order' ? Order::class : Inquiry::class;
        $class::whereKey($this->recordId)->update(['notification_status' => 'failed']);
    }
}
