<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * OrderStatusEnum
 *
 * @category Enums
 * @package  App\Enums

 */
enum OrderStatusEnum: string
{
    case RESERVED  = 'RESERVED';
    case DISPATCHED    = 'DISPATCHED';
    case FINISHED = 'FINISHED';
}//end enum
