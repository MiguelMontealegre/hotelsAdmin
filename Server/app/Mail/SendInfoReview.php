<?php

namespace App\Mail;

use App\Models\User;
use App\Models\WholesaleUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


use App\Models\Review;
/**
 * Class SendConfirmationRegistration
 *
 * @extends  Mailable
 * @category Mail
 * @package  App\Http\Controllers\Auth

 */
class SendInfoReview extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
 
    public Review $review;

    public User $user;

    /**
     * InsightCreate a new message instance.
     *
     * @param User $user
     */
    
     public function __construct(User $user, Review $review)
    {
        $this->user = $user;
        $this->review = $review;

    }//end __construct()


    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'se Hizo una reseña ',
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
            view:'mail/info-review'
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
