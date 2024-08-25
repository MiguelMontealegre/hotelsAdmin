<?php

namespace App\Mail;

use App\Models\User;

use App\Models\WholesaleUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
/**
 * Class SendConfirmationRegistration
 *
 * @extends  Mailable
 * @category Mail
 * @package  App\Http\Controllers\Auth

 */
class SendInfoSaleUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public User $user;
    public Order $sales;


    /**
     * InsightCreate a new message instance.
     *
     * @param User $user
     */
    
    public function __construct(User $user,Order $sales)
    {
        $this->user = $user;
        $this->sales = $sales;

    }//end __construct()


    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comrpobante de Compra',
        );

    }//end envelope()


    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail/info-sale-user'
        );

    }//end content()


    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];

    }//end attachments()


}//end class
