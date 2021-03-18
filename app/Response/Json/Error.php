<?php

/**
 * Response Error
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package XuanHieu
 * @copyright (c) 4.12.2017, HNW
 */

namespace App\Response\Json;

use App\Response\Contracts\ResponseInterface;

class Error extends AbstractJsonResponse implements ResponseInterface
{

    /**
     * Run
     *
     * @param type $factory
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function run($factory)
    {
        $instance = $this;
        $factory->macro('responseError', function ($error, $responseCode = 400, array $containerParams = array()) use ($factory, $instance) {
            $output = array(
                'code' => $responseCode,
                'message' => $error,
                'data' => $containerParams
            );

            return $instance->factoryOutput($factory, $output, $responseCode);
        });
    }

}
