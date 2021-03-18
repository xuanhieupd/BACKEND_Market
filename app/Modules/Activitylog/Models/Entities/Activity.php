<?php

namespace App\Modules\Activitylog\Models\Entities;

use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity as BaseActivity;

class Activity extends BaseActivity
{

    /**
     * Alias for `id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * Message Log
     *
     * @return string
     * @author xuanhieupd
     */
    public function getMessage()
    {
        $subjectInfo = $this->getAttribute('subject');
        if (is_null($subjectInfo)) {
            return 'Undefined';
        }

        $classInstance = new $subjectInfo();
        $methodName = 'getActivity' . Str::studly($this->getAttribute('description')) . 'Message';
        if (!method_exists($classInstance, $methodName)) {
            return 'Undefined Handler';
        }

        return $classInstance->$methodName($this);
    }

    public function getCreatedDate()
    {
        return $this->getAttribute('created_at')->toDateTimeString();
    }

    public function getUpdatedDate()
    {
        return $this->getAttribute('created_at')->toDateTimeString();
    }

}
