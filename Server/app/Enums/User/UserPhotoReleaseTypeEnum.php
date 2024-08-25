<?php
declare(strict_types=1);

namespace App\Enums\User;

/**
 * UserPhotoReleaseTypeEnum
 *
 * @category Enums
 * @package  App\Enums

 */
enum UserPhotoReleaseTypeEnum: string
{
    case NOT_PROVIDED = 'NOT_PROVIDED';
    case OPTED_OUT    = 'OPTED_OUT';
    case OPTED_IN     = 'OPTED_IN';
}//end enum
