<?php

/**
 * Abstract Controller
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Base
 * @copyright (c) 31.10.2020, HNW
 */

namespace App\Base;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AbstractController extends BaseController
{

    /**
     * Response Message
     *
     * @param $message
     * @param array $containerParams
     * @return mixed
     * @author xuanhieupd
     */
    public function responseMessage($message, array $containerParams = array())
    {
        return response()->responseMessage($message, $containerParams);
    }

    /**
     * Response Error
     *
     * @param $error
     * @param int $responseCode
     * @param array $containerParams
     * @return mixed
     * @author xuanhieupd
     */
    public function responseError($error, $responseCode = 400, array $containerParams = array())
    {
        return response()->responseError($error, $responseCode, $containerParams);
    }

    /**
     * No Permission
     *
     * @return mixed
     * @author xuanhieupd
     */
    public function noPermission($message = null)
    {
        $errorMessage = !is_null($message) ? $message : 'Bạn không có quyền thực hiện hành động này';
        return response()->responseError($errorMessage, 403);
    }

    /**
     * Log Activity
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function logActivity(Request $request)
    {

    }

}
