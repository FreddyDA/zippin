<?php

namespace App\Notifications;

use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Orders $order, $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Actualización del estado de su orden')
                    ->greeting('Hola,')
                    ->line('El estado de su orden ha sido actualizado a: ' . $this->order->status)
                    ->line('Detalles de la orden:')
                    ->line('ID de la Orden: ' . $this->order->id)
                    ->line('Fecha de Creación: ' . $this->order->created_at->format('d/m/Y H:i'))
                    ->line('Total: $' . number_format($this->order->total, 2))
                    ->line('Productos:')
                    ->line($this->getOrderItems())
                    ->line('Gracias por elegirnos!');
    }

    protected function getOrderItems()
    {
        $items = '';
        foreach ($this->order->orderItems as $item) {
            $items .= $item->product_name . ' - Cantidad: ' . $item->quantity . ' - Precio: $' . number_format($item->price, 2) . "\n";
        }
        return $items;
    }
}