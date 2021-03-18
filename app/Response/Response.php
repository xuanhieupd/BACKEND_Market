<?php

/**
 * Response
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package XuanHieu
 * @copyright (c) 5.12.2017, HNW
 */

namespace App\Response;

use Illuminate\Contracts\Routing\ResponseFactory;

class Response
{

    /**
     * Macros.
     *
     * @var array
     */
    protected $macros = [];

    /**
     * Constructor.
     *
     * @param ResponseFactory $factory
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function __construct(ResponseFactory $factory)
    {
        $this->macros = $this->_getViewRenderer();
        $this->bindMacros($factory);
    }

    /**
     * Gets the view renderer for the specified response type.
     *
     * @return
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    protected function _getViewRenderer()
    {
        return array(
            Json\Message::class,
            Json\Error::class,
        );
    }

    /**
     * Bind macros.
     *
     * @param ResponseFactory $factory
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function bindMacros($factory)
    {
        foreach ($this->macros as $macro) {
            (new $macro)->run($factory);
        }
    }

}
