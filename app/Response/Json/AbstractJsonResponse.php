<?php

/**
 * Abstract Json Response
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package XuanHieu
 * @copyright (c) 5.12.2017, HNW
 */

namespace App\Response\Json;

use Illuminate\Support\Facades\Auth;

abstract class AbstractJsonResponse {

    /**
     * Add default parameters to the provided parameters array
     *
     * @author shin_conan <xuanhieu.pd@gmail.com>
     * @param array $params
     * @return array
     */
    protected static function _addDefaultParams(array &$params = array()) {
        return $params;
    }

    /**
     * JSON encodes an input for direct output. This renders any objects
     * with string representations to strings.
     *
     * @author shin_conan <xuanhieu.pd@gmail.com>
     * @param mixed $input Data to JSON encode. Likely an array, but not always.
     * @return
     */
    public static function factoryOutput($factory, $input, $statusCode = 200, $addDefaultParams = true) {
        if ($addDefaultParams) {
            self::_addDefaultParams($input);
        }

        return $factory->make($input, $statusCode);
    }

}
