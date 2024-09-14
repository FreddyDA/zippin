<?php

namespace App\Jobs;

use App\Models\Orders;
use Illuminate\Support\Facades\Cache;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyOrderStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $type;
    protected $companyUserEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Orders $order, $type, $companyUserEmail)
    {
        $this->order = $order;
        $this->type = $type;
        $this->companyUserEmail = $companyUserEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Notificar al cliente
        $this->order->customer->notify(new OrderStatusUpdated($this->order, $this->type));

        // Notificar al usuario de la empresa
        Notification::route('mail', $this->companyUserEmail)
            ->notify(new OrderStatusUpdated($this->order, $this->type));
    }
}