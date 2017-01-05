<?php

require_once getShopBasePath() . 'modules/girocheckout/lib/GiroCheckout_SDK/GiroCheckout_SDK.php';

/**
 * Class girocheckout__oxorder
 */
class girocheckout__oxorder extends girocheckout__oxorder_parent {

    /**
     * if girocheckout transaction (not GiroTrust)
     * don't send order email under normal workflow
     *
     * @param null $oUser
     * @param null $oBasket
     * @param null $oPayment
     *
     * @return bool|int
     */
    protected function _sendOrderByEmail($oUser = null, $oBasket = null, $oPayment = null) {
        if ($this->girocheckout__isGiroCheckoutOrder() && !$this->girocheckout__isPaymentDone()) {

            return self::ORDER_STATE_OK;
        }
        return parent::_sendOrderByEmail($oUser, $oBasket, $oPayment);
    }

    /**
     * separate method to send girocheckout order email
     * once order status = OK
     *
     */
    public function sendGiroCheckoutOrderByEmail() {
        $this->_oBasket = $this->_getRecalculatedBasket();
        $this->_oUser = $this->_getUserFromOrder();

        $this->_oPayment = $this->getPaymentType();
        $this->_sendOrderByEmail($this->_oUser, $this->_oBasket, $this->_oPayment);
    }

    /**
     * @return bool
     */
    public function girocheckout__isGiroCheckoutOrder() {
        return stristr($this->oxorder__oxpaymenttype->value, 'gc_') !== false;
    }

    /**
     * @return bool
     */
    public function girocheckout__isPaymentDone() {
        // if status from girocheckout is available, payment is done
        if (empty($this->oxorder__giroconnect__status->value)) {
            return false;
        }

        return true;
    }

    /**
     * @return oxBasket
     */
    protected function _getRecalculatedBasket() {
        $oBasket = oxRegistry::getSession()->getBasket();
        $oBasketArticles = $oBasket->getBasketArticles();

        if (count($oBasketArticles) > 0) {
            return $oBasket;
        }

        $oBasket = $this->_getOrderBasket();

        // add this order articles to virtual basket and recalculates basket
        #$this->_addOrderArticlesToBasket($oBasket, $this->getOrderArticles(true));
        $this->_addArticlesToBasket($oBasket, $this->getOrderArticles(true));

        // recalculating basket
        $oBasket->calculateBasket(true);
        return $oBasket;
    }

    /**
     * @return null|oxUser
     */
    protected function _getUserFromOrder() {
        $oUser = NULL;
        $oUser = oxRegistry::getSession()->getUser();
        if ($oUser != NULL) {
            if ($oUser->isLoaded() == true) {
                return $oUser;
            }
        }
        $oUser = $this->_oBasket->getBasketUser();
        return $oUser;
    }

    /**
     * @param      $sMaxField
     * @param null $aWhere
     * @param int  $iMaxTryCnt
     */
    protected function _setRecordNumber_($sMaxField, $aWhere = null, $iMaxTryCnt = 5) {
        /** @var girocheckout__order_number_reservation $orderNumberReservation */
        $orderNumberReservation = oxNew('girocheckout__order_number_reservation');
        do {
            // as long as a reservation exists for the current order number
            // create a new order number
            parent::_setRecordNumber($sMaxField, $aWhere, $iMaxTryCnt);

            $reservationExists = $orderNumberReservation->load(
                    girocheckout__order_number_reservation::getReservationKey($this->oxorder__oxordernr->value)
            );
        } while ($reservationExists);
    }

    /**
     * Creates and returns user payment.
     *
     * @param string $sPaymentid used payment id
     *
     * @return oxUserPayment
     */
    function setPayment($paymentId) {

        return $this->_setPayment($paymentId);
    }

}
