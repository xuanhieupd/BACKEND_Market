<?php

namespace App\Modules\Store\Modules\SettingUser\Constants;

class Constants
{

    const SETTING_DISPLAY_NAME_ID = 'DISPLAY';

    const DISPLAY_PRICE = 'DISPLAY_PRICE'; // Chỉ hiển thị giá
    const DISPLAY_QUANTITY_PRICE = 'DISPLAY_QUANTITY_PRICE'; // Hiển thị cả giá + số lượng
    const DISPLAY_NONE = 'DISPLAY_NONE'; // Không hiển thị gì
    const DISPLAY_APPROXIMATE = 'DISPLAY_APPROXIMATE'; // Hiển thị số lượng áng chừng

    /**
     * Tất cả loại option tùy chọn hiển thị
     *
     * @return array
     * @author xuanhieupd
     */
    public static function getDisplays()
    {
        return array(
            self::DISPLAY_NONE,
            self::DISPLAY_PRICE,
            self::DISPLAY_QUANTITY_PRICE,
            self::DISPLAY_APPROXIMATE,
        );
    }

    /**
     * Với giá trị $displayId thì có được xem giá hay không ?
     *
     * @param $displayId
     * @return bool
     * @author xuanhieupd
     */
    public static function canViewPrice($displayId)
    {
        return in_array($displayId, array(
            self::DISPLAY_PRICE,
            self::DISPLAY_QUANTITY_PRICE,
        ));
    }

    /**
     * Với giá trị $displayId thì có được xem số lượng hay không ?
     *
     * @param $displayId
     * @return bool
     * @author xuanhieupd
     */
    public static function canViewQuantity($displayId)
    {
        return in_array($displayId, array(
            self::DISPLAY_QUANTITY_PRICE,
            self::DISPLAY_APPROXIMATE,
        ));
    }

    /**
     * Display Title
     *
     * @param string $displayId
     * @return string
     * @author xuanhieupd
     */
    public static function getDisplayTitle($displayId)
    {
        switch ($displayId) {
            case self::DISPLAY_NONE:
                return 'Không hiển thị gì';

            case self::DISPLAY_QUANTITY_PRICE:
                return 'Giá + Số lượng';

            case self::DISPLAY_PRICE:
                return 'Chỉ giá';

            case self::DISPLAY_APPROXIMATE:
                return 'Số lượng áng chừng';
        }

        return 'Chưa thiết lập';
    }

}
