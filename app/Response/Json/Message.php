<?php

/**
 * Response Message
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package XuanHieu
 * @copyright (c) 4.12.2017, HNW
 */

namespace App\Response\Json;

use App\Response\Contracts\ResponseInterface;

class Message extends AbstractJsonResponse implements ResponseInterface
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
        $factory->macro('responseMessage', function ($message, array $messageParams = array()) use ($factory, $instance) {
            $messageResult = array(
                'code' => 200,
                'message' => $message,
                'data' => $messageParams
            );

            return $instance->factoryOutput($factory, $messageResult);
        });
    }

}
