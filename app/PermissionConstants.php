<?php

namespace App;

class PermissionConstants
{

    const CART_SUBMIT = 'CART_SUBMIT'; // Xác nhận giỏ hàng

    const ORDER_VIEW_PENDING = 'ORDER_VIEW_PENDING'; // Nhân viên bán hàng gửi giỏ hàng tới cho quản lý
    const ORDER_VIEW_PENDING_WAREHOUSE = 'ORDER_VIEW_PENDING_WAREHOUSE'; // Nhân viên kho đang nhặt hàng
    const ORDER_VIEW_WAREHOUSE_CONFIRM = 'ORDER_VIEW_WAREHOUSE_CONFIRM'; // Nhân viên kho xác nhận
    const ORDER_VIEW_DONE = 'ORDER_VIEW_DONE'; // Đơn hàng hoàn tất

    const ORDER_VIEW_DETAIL = 'ORDER_VIEW_DETAIL'; // Xem chi tiết toa
    const ORDER_DELETE = 'ORDER_DELETE'; // Xóa toa

    const PURCHASE_DELETE  = 'PURCHASE_DELETE';

}
