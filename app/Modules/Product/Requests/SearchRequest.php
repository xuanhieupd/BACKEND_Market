<?php

namespace App\Modules\Product\Requests;

use App\Base\AbstractRequest;

class SearchRequest extends AbstractRequest
{

    /**
     * Rules
     *
     * @return string[]
     * @author xuanhieupd
     */
    public function rules()
    {
        return array();
    }

    /**
     * Từ khóa tìm kiếm
     *
     * @return string
     * @author xuanhieupd
     */
    public function getSearchQuery()
    {
        return $this->get('search', '');
    }

}
