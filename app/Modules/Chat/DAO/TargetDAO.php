<?php

namespace App\Modules\Chat\DAO;

class TargetDAO
{

    private $target;
    private $targetIds;

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target): void
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getTargetIds()
    {
        return $this->targetIds;
    }

    /**
     * @param mixed $targetIds
     */
    public function setTargetIds($targetIds): void
    {
        $this->targetIds = $targetIds;
    }



}
