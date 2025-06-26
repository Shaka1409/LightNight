<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Orders;

class confirmPayment extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    /**
     * Create a new message instance.
     */
   
public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name')) // Sử dụng mail from từ .env
                    ->subject('Báo cáo đơn hàng từ cửa hàng - #' . $this->order->id)
                    ->markdown('emails.orders.confirm_payment') 
                    ->with([
                        'order' => $this->order,
                    ]);
    }
}