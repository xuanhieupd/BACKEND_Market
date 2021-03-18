<?php

/**
 * Abstract Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Base
 * @copyright (c) 31.10.2020, HNW
 */

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

abstract class AbstractRepository
{

    /**
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Constructor
     *
     * @return void
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function __construct()
    {
        $this->app = new App();

        $this->makeModel();
    }

    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $this->model = $model;
        return $this;
    }

    /**
     * @param $method
     * @param $parameters
     *
     * Forward all method calls to \Illuminate\Database\Eloquent\Model
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->model, $method), $parameters);
    }

    abstract public function model();

    /**
     * Điều kiện
     *
     * @param array $conditions
     * @return $this
     * @author xuanhieupd
     */
    public function conditions(array $conditions)
    {
        return $this;
    }

    /**
     * Tùy chọn
     *
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function options(array $fetchOptions)
    {
        if (isset($fetchOptions['withs']) && is_array($fetchOptions['withs'])) {
            $this->model = $this->model->with($fetchOptions['withs']);
        }

        if (isset($fetchOptions['fields']) && is_array($fetchOptions['fields'])) {
            $this->model = $this->model->select($fetchOptions['fields']);
        }

        return $this;
    }
}
