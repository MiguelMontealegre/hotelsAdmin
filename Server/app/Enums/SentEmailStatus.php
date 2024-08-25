<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * SentEmailStatus
 *
 * @category Enums
 * @package  App\Enums
 * @author   CJ Vargas <carlos.vargas@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
enum SentEmailStatus: string
{
    case SENT      = 'SENT';
    case ACCEPTED  = 'ACCEPTED';
    case BOUNCED   = 'BOUNCED';
    case QUEUE     = 'QUEUE';
    case DELIVERED = 'DELIVERED';
    case FAILED    = 'FAILED';

}//end enum
