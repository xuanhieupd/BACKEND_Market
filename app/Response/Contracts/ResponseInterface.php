<?php

/**
 * Response Interface
 * 
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package XuanHieu
 * @copyright (c) 4.12.2017, HNW
 */

namespace App\Response\Contracts;

interface ResponseInterface {

    /**
     * Run.
     * 
     * @author shin_conan <xuanhieu.pd@gmail.com>
     * @param  \Illuminate\Routing\ResponseFactory $factory
     */
    public function run($factory);
}
