<?php

namespace App\Modules\Order\DAO\Request;

use Illuminate\Http\Request;

class FilterOrderRequest
{

    /**
     * @var Request
     */
    private $request;

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Tìm kiếm theo từ khóa
     *
     * @return string
     * @author xuanhieupd
     */
    public function getSearch()
    {
        return '';
    }

    /**
     * Tìm kiếm theo khách hàng
     *
     * @return int
     * @author xuanhieupd
     */
    public function getCustomerId()
    {
        return '';
    }

    /**
     * Tìm kiếm theo sản phẩm
     *
     * @return int
     * @author xuanhieupd
     */
    public function getProductId()
    {
        return '';
    }

    /**
     * Tìm kiếm theo khoảng ngày
     *
     * @return array
     * @author xuanhieupd
     */
    public function getDates()
    {
        return '';
    }


    /**
     * Lọc theo trạng thái toa hàng
     *
     * @return int
     * @author xuanhieupd
     */
    public function getOrderStatus()
    {

    }

    /**
     * Lấy danh sách nhân viên cấp dưới của user hiện tại
     *
     * @param $userInfo
     * @return array
     * @author xuanhieupd
     */
    protected function getChildUserIds($userInfo)
    {
        $fetchOptions = array('fields' => array('user_id'));
        $childUsers = $this->userRepo->getStoreChildUsersBySupervisor($userInfo->getStoreId(), $userInfo, array(), $fetchOptions);

        return $childUsers ? $childUsers->pluck('user_id')->toArray() : array();
    }

}
