<?php

/**
 * Class girocheckout__order_number_reservation
 */
class girocheckout__order_number_reservation extends oxBase
{

    /**
     * Object core table name
     *
     * @var string
     */
    protected $_sCoreTable = 'giroconnect__order_number_reservations';

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'girocheckout__order_number_reservation';

    /**
     * @param $orderNr
     *
     * @return string
     */
    public static function getReservationKey($orderNr)
    {
        if (oxRegistry::getConfig()->getConfigParam('blSeparateNumbering')) {
            return $orderNr . '-' . oxRegistry::getConfig()->getShopId();
        }

        return $orderNr;
    }
}
