<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceGenerated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice->only(['id', 'URL', 'status', 'details']); 
    }

    public function broadcastOn()
    {
        return new Channel('invoices');
    }

    public function broadcastAs()
    {
        return 'invoice.created';
    }
}
