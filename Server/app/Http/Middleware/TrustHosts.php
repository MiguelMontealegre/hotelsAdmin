<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

/**
 * Class TrustHosts
 *
 * @category Middleware
 * @package  App\Http\Middleware

 */
class TrustHosts extends Middleware
{


    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts()
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];

    }//end hosts()


}//end class
