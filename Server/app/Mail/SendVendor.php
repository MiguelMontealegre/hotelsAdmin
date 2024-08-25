<?php

namespace App\Mail;

use App\Models\User;
use App\Models\WholesaleUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\VendorProducts;

/**
 * Class SendConfirmationRegistration
 *
 * @extends  Mailable
 * @category Mail
 * @package  App\Http\Controllers\Auth

 */
class SendVendor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public VendorProducts $vendorProduct;


    /**
     * InsightCreate a new message instance.
     *
     * @param VendorProducts $vendorProduct
     */
    
    public function __construct(VendorProducts $vendorProduct)
    {
        $this->vendorProduct = $vendorProduct;

    }//end __construct()

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Solicitud de Proveedor',
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
            view: 'mail/confirm-vendor'
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
