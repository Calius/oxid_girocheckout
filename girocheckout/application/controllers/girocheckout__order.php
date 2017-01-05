<?php

require_once getShopBasePath() . 'modules/girocheckout/lib/GiroCheckout_SDK/GiroCheckout_SDK.php';

/**
 * Class girocheckout__order
 */
class girocheckout__order extends girocheckout__order_parent {

    /**
     * Returns array of payment methods by GiroCheckout
     *
     * @return array $aGCPaymentMethods
     */
    protected $aGCPaymentMethods = array(
        'gc_creditcard' => 'gc_creditcard',
        'gc_giropay' => 'gc_giropay',
        'gc_eps' => 'gc_eps',
        'gc_directdebit' => 'gc_directdebit',
        'gc_ideal' => 'gc_ideal',
        'gc_paydirekt' => 'gc_paydirekt',
        'gc_sofortuw' => 'gc_sofortuw');

    /**
     * @param int $iSuccess
     *
     * @return string
     */
    protected function _getNextStep($iSuccess) {

// get current language
        $sActualPayment = $this->getPayment()->oxpayments__oxid->value;

        $oOrder = oxNew('oxorder');

        $sess_challenge = oxRegistry::getSession()->getVariable("sess_challenge");

        if ($oOrder->load($sess_challenge) && in_array($sActualPayment, $this->aGCPaymentMethods)) {
            if (is_numeric($iSuccess) && $iSuccess >= 1) {
                if ($iSuccess === oxOrder::ORDER_STATE_ORDEREXISTS && !$oOrder->girocheckout__isPaymentDone()) {
                    list($oOrder, $iSuccess) = $this->girocheckout__recreateOrder($oOrder);
                }
                $oBasket = $this->getBasket();
                $dAmount = $oBasket->getPrice()->getBruttoPrice(); //amount
                // get currency
                $oCur = $oBasket->getBasketCurrency();
                $sCur = $oCur->name;

                $iOrderId = $oOrder->oxorder__oxid->value; //orderid (char 32)
                
                $lang = oxRegistry::getLang()->getLanguageAbbr(); //lang
                //
                //get version of the plugin and the active shop.
                $sourceParam = $this->getGcSource();

                // generate transaction id to identify the transaction on notify and redirect
                $transactionId = oxUtilsObject::getInstance()->generateUID();

                // get values from payment select page
                $aDynvalue = oxRegistry::getSession()->getVariable('dynvalue');

                $sStoken = $this->getSession()->getSessionChallengeToken();
                $sRtoken = $this->getSession()->getRemoteAccessToken(true);
                $urlRedirect = $this->getConfig()->getSslShopUrl() . 'index.php?cl=order&fnc=processGiroCheckoutRedirect&pm='
                        . $this->getPayment()->getId() . '&sess_challenge=' . oxRegistry::getSession()->getVariable('sess_challenge')
                        . '&' . $this->getSession()->getName() . '=' . $this->getSession()->getId() . '&stoken=' . $sStoken
                        . '&rtoken=' . $sRtoken;
                $urlNotify = $this->getConfig()->getSslShopUrl() . 'index.php?cl=order&fnc=processGiroCheckoutNotify&pm='
                        . $this->getPayment()->getId() . '&sess_challenge=' . oxRegistry::getSession()->getVariable('sess_challenge')
                        . '&' . $this->getSession()->getName() . '=' . $this->getSession()->getId() . '&stoken=' . $sStoken
                        . '&rtoken=' . $sRtoken;


                $oUser = $this->getUser();
                $iUserId = $oUser->oxuser__oxid->value; //customer id (char 32)
                // check that this is a creditcard payment
                if ($this->getPayment()->getId() == "gc_creditcard") {

                    // change order status
                    $oOrder->oxorder__oxtransstatus = new oxField('IN PAYMENT');
                    $oOrder->oxorder__giroconnect__transaction_id = new oxField($transactionId);
                    $oOrder->setPayment($oBasket->getPaymentId());
                    $oOrder->save();

                    try {
                        $sPurpose = $this->getPurpose(oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_PURPOSE'), $oOrder, $oUser);

                        //Sends request to Girocheckout.
                        $request = new GiroCheckout_SDK_Request('creditCardTransaction');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_PROJECTID'))
                                ->addParam('merchantTxId', $transactionId)
                                ->addParam('amount', round($dAmount * 100))
                                ->addParam('currency', strtoupper($sCur))
                                ->addParam('purpose', $sPurpose)
                                ->addParam('locale', $lang)
                                ->addParam('urlRedirect', $urlRedirect)
                                ->addParam('urlNotify', $urlNotify)
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('orderId', $iOrderId)
                                ->addParam('customerId', $iUserId)
                                //the hash field is auto generated by the SDK
                                ->submit();

                        if (!$request->requestHasSucceeded()) {
// change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('rc'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        } else {
                            $strUrlRedirect = $request->getResponseParam('redirect');

//          oxUtils::getInstance()->redirect($strUrlRedirect);
                            oxRegistry::getUtils()->redirect($strUrlRedirect, false);
                        }
                    } catch (Exception $e) {
                        $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                        $oOrder->save();
                        oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
                        return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                    }
                }

                // check that this is a directdebit payment
                if ($this->getPayment()->getId() == "gc_directdebit") {

                    // change order status
                    $oOrder->oxorder__oxtransstatus = new oxField('IN PAYMENT');
                    $oOrder->oxorder__giroconnect__transaction_id = new oxField($transactionId);
                    $oOrder->setPayment($oBasket->getPaymentId());
                    $oOrder->save();

                    if (strlen($aDynvalue['gc_directdebit_accountholder'] > 27)) {
                        $aDynvalue['gc_directdebit_accountholder'] = substr($aDynvalue['gc_directdebit_accountholder'], 0, 27);
                    }

                    try {
                        $sPurpose = $this->getPurpose(oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_PURPOSE'), $oOrder, $oUser);

                        //Sends request to Girocheckout.
                        if ($aDynvalue['gc_directdebit_cdatabankcheck'] == "rbBankDatasDirectdebit") {

                            $request = new GiroCheckout_SDK_Request('directDebitTransaction');
                            $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_SECRET'));
                            $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_MERCHANTID'))
                                    ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_PROJECTID'))
                                    ->addParam('merchantTxId', $transactionId)
                                    ->addParam('amount', round($dAmount * 100))
                                    ->addParam('currency', strtoupper($sCur))
                                    ->addParam('purpose', $sPurpose)
                                    ->addParam('bankcode', $aDynvalue['gc_directdebit_bankcode'])
                                    ->addParam('bankaccount', $aDynvalue['gc_directdebit_account'])
                                    ->addParam('accountHolder', $aDynvalue['gc_directdebit_accountholder'])
                                    ->addParam('sourceId', $sourceParam)
                                    ->addParam('orderId', $iOrderId)
                                    ->addParam('customerId', $iUserId)
                                    ->submit();
                        } else if (empty($aDynvalue['gc_directdebit_cdatabankcheck']) || $aDynvalue['gc_directdebit_cdatabankcheck'] == 'rbIbanDirectdebit' || !oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_SHOWBANK')) {

                            $request = new GiroCheckout_SDK_Request('directDebitTransaction');
                            $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_SECRET'));
                            $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_MERCHANTID'))
                                    ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_PROJECTID'))
                                    ->addParam('merchantTxId', $transactionId)
                                    ->addParam('amount', round($dAmount * 100))
                                    ->addParam('currency', strtoupper($sCur))
                                    ->addParam('purpose', $sPurpose)
                                    ->addParam('iban', $aDynvalue['gc_directdebit_iban'])
                                    ->addParam('accountHolder', $aDynvalue['gc_directdebit_accountholder'])
                                    ->addParam('sourceId', $sourceParam)
                                    ->addParam('orderId', $iOrderId)
                                    ->addParam('customerId', $iUserId)
                                    ->submit();
                        }

                        if ($request->requestHasSucceeded() && $request->paymentSuccessful()) {
                            $oOrder->oxorder__oxtransstatus = new oxField('OK');
                            $oOrder->oxorder__giroconnect__status = new oxField($iResPayment);
                            $oOrder->oxorder__oxpaid = new oxField(
                                    oxRegistry::get("oxUtilsDate")->formatDBDate(date("Y-m-d H:i:s"), true)
                            );
                            $oOrder->save();

                            oxRegistry::getSession()->setVariable('girocheckout_disable_article_check', '1');
                            $oOrder->sendGiroCheckoutOrderByEmail();
                            oxRegistry::getSession()->deleteVariable('girocheckout_disable_article_check');
                        } elseif (!$request->requestHasSucceeded()) {
                            // change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            $oOrder->cancelOrder();
                            oxRegistry::getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID()); // <-- forces new order creation
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('rc'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        } else if (!$request->paymentSuccessful()) {
                            // change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            $oOrder->cancelOrder();
                            oxRegistry::getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID()); // <-- forces new order creation
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('resultPayment'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        }
                    } catch (Exception $e) {
                        $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                        $oOrder->save();
                        $oOrder->cancelOrder();
                        oxRegistry::getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID()); // <-- forces new order creation
                        oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
                        return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                    }
                }

                // check that this is a eps payment
                if ($this->getPayment()->getId() == "gc_eps") {

                    // change order status
                    $oOrder->oxorder__oxtransstatus = new oxField('IN PAYMENT');
                    $oOrder->oxorder__giroconnect__transaction_id = new oxField($transactionId);
                    $oOrder->setPayment($oBasket->getPaymentId());
                    $oOrder->save();

                    try {
                        $sPurpose = $this->getPurpose(oxRegistry::getConfig()->getConfigParam('GC_EPS_PURPOSE'), $oOrder, $oUser);

                        //Sends request to Girocheckout.
                        $request = new GiroCheckout_SDK_Request('epsTransaction');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_EPS_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_EPS_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_EPS_PROJECTID'))
                                ->addParam('merchantTxId', $transactionId)
                                ->addParam('amount', round($dAmount * 100))
                                ->addParam('currency', strtoupper($sCur))
                                ->addParam('purpose', $sPurpose)
                                ->addParam('bic', $aDynvalue['gc_eps_bic'])
                                ->addParam('urlRedirect', $urlRedirect)
                                ->addParam('urlNotify', $urlNotify)
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('orderId', $iOrderId)
                                ->addParam('customerId', $iUserId)
                                ->submit();

                        if (!$request->requestHasSucceeded()) {
// change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('rc'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        } else {
                            $strUrlRedirect = $request->getResponseParam('redirect');

//          oxUtils::getInstance()->redirect($strUrlRedirect);
                            oxRegistry::getUtils()->redirect($strUrlRedirect, false);
                        }
                    } catch (Exception $e) {
                        $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                        $oOrder->save();
                        oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
                        return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                    }
                }

                // check that this is a giropay payment
                if ($this->getPayment()->getId() == "gc_giropay") {

                    // change order status
                    $oOrder->oxorder__oxtransstatus = new oxField('IN PAYMENT');
                    $oOrder->oxorder__giroconnect__transaction_id = new oxField($transactionId);
                    $oOrder->setPayment($oBasket->getPaymentId());
                    $oOrder->save();

                    try {
                        $sPurpose = $this->getPurpose(oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_PURPOSE'), $oOrder, $oUser);

                        //Sends request to Girocheckout.
                        $request = new GiroCheckout_SDK_Request('giropayTransaction');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_PROJECTID'))
                                ->addParam('merchantTxId', $transactionId)
                                ->addParam('amount', round($dAmount * 100))
                                ->addParam('currency', strtoupper($sCur))
                                ->addParam('purpose', $sPurpose)
                                ->addParam('bic', $aDynvalue['gc_giropay_bic'])
                                ->addParam('urlRedirect', $urlRedirect)
                                ->addParam('urlNotify', $urlNotify)
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('orderId', $iOrderId)
                                ->addParam('customerId', $iUserId)
                                //the hash field is auto generated by the SDK
                                ->submit();

                        if (!$request->requestHasSucceeded()) {
                            // change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('rc'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        } else {
                            $strUrlRedirect = $request->getResponseParam('redirect');

                            oxRegistry::getUtils()->redirect($strUrlRedirect, false);
                        }
                    } catch (Exception $e) {
                        $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                        $oOrder->save();
                        oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
                        return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                    }
                }

                // check that this is a iDEAL payment
                if ($this->getPayment()->getId() == "gc_ideal") {

                    // change order status
                    $oOrder->oxorder__oxtransstatus = new oxField('IN PAYMENT');
                    $oOrder->oxorder__giroconnect__transaction_id = new oxField($transactionId);
                    $oOrder->setPayment($oBasket->getPaymentId());
                    $oOrder->save();

                    try {
                        $sPurpose = $this->getPurpose(oxRegistry::getConfig()->getConfigParam('GC_IDEAL_PURPOSE'), $oOrder, $oUser);

                        //Sends request to Girocheckout.
                        $request = new GiroCheckout_SDK_Request('idealPayment');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_IDEAL_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_IDEAL_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_IDEAL_PROJECTID'))
                                ->addParam('merchantTxId', $transactionId)
                                ->addParam('amount', round($dAmount * 100))
                                ->addParam('currency', strtoupper($sCur))
                                ->addParam('purpose', $sPurpose)
                                ->addParam('issuer', $aDynvalue['gc_ideal_issuer'])
                                ->addParam('urlRedirect', $urlRedirect)
                                ->addParam('urlNotify', $urlNotify)
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('orderId', $iOrderId)
                                ->addParam('customerId', $iUserId)
                                ->submit();


                        if (!$request->requestHasSucceeded()) {
                            // change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('rc'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        } else {
                            $strUrlRedirect = $request->getResponseParam('redirect');

                            oxRegistry::getUtils()->redirect($strUrlRedirect, false);
                        }
                    } catch (Exception $e) {
                        $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                        $oOrder->save();
                        oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
                        return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                    }
                }

                // check that this is a paydirekt payment
                if ($this->getPayment()->getId() == "gc_paydirekt") {

                    // change order status
                    $oOrder->oxorder__oxtransstatus = new oxField('IN PAYMENT');
                    $oOrder->oxorder__giroconnect__transaction_id = new oxField($transactionId);
                    $oOrder->setPayment($oBasket->getPaymentId());
                    $oOrder->save();

                    try {
                        $sPurpose = $this->getPurpose(oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_PURPOSE'), $oOrder, $oUser);

                        $aShippingData = $this->getShippingData($oOrder);

                        $orderAmount = round(($dAmount - $aShippingData["shippingAmount"]) * 100);

                        $oCart = new GiroCheckout_SDK_Request_Cart();
                        $aBasketContents = $this->getBasket()->getContents();
                        
                        $oOrderNumber = $oOrder->oxorder__oxordernr->value;

                        if (!empty($aBasketContents)) {
                            foreach ($aBasketContents as $oBasketItem) {
                                $sEAN = "";
                                $oArticle = $oBasketItem->getArticle();
                                $sEAN = $oArticle->oxarticles__oxean->value;
                                

                                $sNombre = strlen($oBasketItem->getTitle()) > 100 ? substr($oBasketItem->getTitle(), 0, 90) . '...' : $oBasketItem->getTitle();

                                $iPrice = round($oBasketItem->getUnitPrice()->getBruttoPrice() * 100);

                                $oCart->addItem($sNombre, $oBasketItem->getAmount(), $iPrice, $sEAN);

                            }
                        }

                        //Sends request to Girocheckout.
                        $request = new GiroCheckout_SDK_Request('paydirektTransaction');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_PROJECTID'))
                                ->addParam('merchantTxId', $transactionId)
                                ->addParam('amount', round($dAmount * 100))
                                ->addParam('currency', strtoupper($sCur))
                                ->addParam('purpose', $sPurpose)
                                //->addParam('type', 'SALE')
                                ->addParam('shippingAmount', round($aShippingData["shippingAmount"] * 100))
                                ->addParam('shippingAddresseFirstName', $aShippingData["shippingAddresseFirstName"])
                                ->addParam('shippingAddresseLastName', $aShippingData["shippingAddresseLastName"])
                                ->addParam('shippingCompany', $aShippingData["shippingCompany"])
                                ->addParam('shippingAdditionalAddressInformation', $aShippingData["shippingAdditionalAddressInformation"])
                                ->addParam('shippingStreet', $aShippingData["shippingStreet"])
                                ->addParam('shippingZipCode', $aShippingData["shippingZipCode"])
                                ->addParam('shippingCity', $aShippingData["shippingCity"])
                                ->addParam('shippingCountry', $aShippingData["shippingCountryIso"])
                                ->addParam('orderAmount', $orderAmount)
                                ->addParam('cart', $oCart)
                                ->addParam('urlRedirect', $urlRedirect)
                                ->addParam('urlNotify', $urlNotify)
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('orderId', $oOrderNumber)
                                ->addParam('customerId', $iUserId)
                                ->addParam('customerMail', $oOrder->oxorder__oxbillemail->value)
                                //the hash field is auto generated by the SDK
                                ->submit();

                        if (!$request->requestHasSucceeded()) {
                            // change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('rc'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        } else {
                            $strUrlRedirect = $request->getResponseParam('redirect');

                            oxRegistry::getUtils()->redirect($strUrlRedirect, false);
                        }
                    } catch (Exception $e) {
                        $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                        $oOrder->save();
                        oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
                        return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                    }
                }

                // check that this is a SOFORT Überweisung payment
                if ($this->getPayment()->getId() == "gc_sofortuw") {

                    // change order status
                    $oOrder->oxorder__oxtransstatus = new oxField('IN PAYMENT');
                    $oOrder->oxorder__giroconnect__transaction_id = new oxField($transactionId);
                    $oOrder->setPayment($oBasket->getPaymentId());
                    $oOrder->save();

                    try {
                        $sPurpose = $this->getPurpose(oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_PURPOSE'), $oOrder, $oUser);

                        //Sends request to Girocheckout.
                        $request = new GiroCheckout_SDK_Request('sofortuwTransaction');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_PROJECTID'))
                                ->addParam('merchantTxId', $transactionId)
                                ->addParam('amount', round($dAmount * 100))
                                ->addParam('currency', strtoupper($sCur))
                                ->addParam('purpose', $sPurpose)
                                ->addParam('urlRedirect', $urlRedirect)
                                ->addParam('urlNotify', $urlNotify)
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('orderId', $iOrderId)
                                ->addParam('customerId', $iUserId)
                                ->submit();

                        if (!$request->requestHasSucceeded()) {
                            // change order status
                            $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                            $oOrder->save();
                            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $request->getResponseMessage($request->getResponseParam('rc'), $lang));
                            return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                        } else {
                            $strUrlRedirect = $request->getResponseParam('redirect');

                            oxRegistry::getUtils()->redirect($strUrlRedirect, false);
                        }
                    } catch (Exception $e) {
                        $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                        $oOrder->save();
                        oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
                        return parent::_getNextStep(oxOrder::ORDER_STATE_PAYMENTERROR);
                    }
                }
            }
        }
        return parent::_getNextStep($iSuccess);
    }

    /**
     * - order nr reservieren
     * - order löschen
     * - order objekt erstellen
     * - reservierte order nr übernehmen
     * - order finalisieren ($oOrder->finalizeOrder($this->getBasket(), $this->getUser()))
     * - onOrderExecute ($oUser->onOrderExecute($oBasket, $iSuccess))
     *
     * @param oxOrder $oOrder
     *
     * @return array
     */
    protected function girocheckout__recreateOrder(oxOrder $oOrder) {
        $oOrderNumber = $oOrder->oxorder__oxordernr->value;

// create order number reservation
        /** @var girocheckout__order_number_reservation $oOrderNumberReservation */
        $oOrderNumberReservation = oxNew('girocheckout__order_number_reservation');
        $reservationKey = girocheckout__order_number_reservation::getReservationKey($oOrderNumber);
        if (!$oOrderNumberReservation->load($reservationKey)) {
            $oOrderNumberReservation->setId($reservationKey);
            $oOrderNumberReservation->save();
        }

        $oOrder->delete();
        /** @var oxorder $newOrder */
        $newOrder = oxNew('oxorder');
        $newOrder->oxorder__oxordernr = new oxField($oOrderNumber, oxField::T_RAW);
        $iSuccess = $newOrder->finalizeOrder($this->getBasket(), $this->getUser());
        $this->getUser()->onOrderExecute($this->getBasket(), $iSuccess);

        // delete order number reservation
        $oOrderNumberReservation->delete();

        return array($newOrder, $iSuccess);
    }

    /**
     * handles GiroCheckout notify action.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * 
     */
    public function processGiroCheckoutNotify() {
        if (empty($_GET['pm'])) {
            header('HTTP/1.1 400 Bad Request');
            echo "param pm not found.";
            exit;
        }

        $pm = $_GET['pm'];

        $sess_challenge = $_GET['sess_challenge'];

        /** @var girocheckout__oxorder $oOrder */
        $oOrder = oxNew('oxorder');
        $oOrder->load($sess_challenge);

        $iUserId = $oOrder->oxorder__oxuserid->value;
        $iOrderId = $oOrder->oxorder__oxid->value;

        try {
            $notify = null;

            if ($pm == "gc_creditcard") {
                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('creditCardTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_giropay") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('giropayTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_ideal") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('idealPayment');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_IDEAL_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_eps") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('epsTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_EPS_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_paydirekt") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('paydirektTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_sofortuw") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('sofortuwTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_SECRET'));
                $notify->parseNotification($_GET);
            }

            if (!$oOrder->isLoaded()) {
                $notify->sendBadRequestStatus();
                $notify->setNotifyResponseParam('Result', 'ERROR');
                $notify->setNotifyResponseParam('ErrorMessage', 'Order not loaded.');
                $notify->setNotifyResponseParam('MailSent', '');
                $notify->setNotifyResponseParam('OrderId', $iOrderId);
                $notify->setNotifyResponseParam('CustomerId', $iUserId);
                echo $notify->getNotifyResponseStringJson();
                exit;
            }

            if ($oOrder->oxorder__giroconnect__transaction_id != $_GET['gcMerchantTxId']) {
                $notify->sendBadRequestStatus();
                $notify->setNotifyResponseParam('Result', 'ERROR');
                $notify->setNotifyResponseParam('ErrorMessage', 'Order tx id does not match merchantTxId.');
                $notify->setNotifyResponseParam('MailSent', '');
                $notify->setNotifyResponseParam('OrderId', $iOrderId);
                $notify->setNotifyResponseParam('CustomerId', $iUserId);
                echo $notify->getNotifyResponseStringJson();
                exit;
            }

            if ($oOrder->oxorder__oxpaymenttype != $pm) {
                $notify->sendBadRequestStatus();
                $notify->setNotifyResponseParam('Result', 'ERROR');
                $notify->setNotifyResponseParam('ErrorMessage', 'Parameter pm not valid for order.');
                $notify->setNotifyResponseParam('MailSent', '');
                $notify->setNotifyResponseParam('OrderId', $iOrderId);
                $notify->setNotifyResponseParam('CustomerId', $iUserId);
                echo $notify->getNotifyResponseStringJson();
                exit;
            }




            if (!$notify->paymentSuccessful()) {
                $oOrder->oxorder__oxtransstatus = new oxField('ERROR');
                $oOrder->oxorder__giroconnect__status = new oxField($notify->getResponseParam('gcResultPayment'));
                $oOrder->save();
                $oOrder->cancelOrder();

                $notify->sendOkStatus();
                $notify->setNotifyResponseParam('Result', 'OK');
                $notify->setNotifyResponseParam('ErrorMessage', '');
                $notify->setNotifyResponseParam('MailSent', '');
                $notify->setNotifyResponseParam('OrderId', $iOrderId);
                $notify->setNotifyResponseParam('CustomerId', $iUserId);
                echo $notify->getNotifyResponseStringJson();
                exit;
            } else {
                $oOrder->oxorder__oxtransstatus = new oxField('OK');
                $oOrder->oxorder__giroconnect__status = new oxField($notify->getResponseParam('gcResultPayment'));
                $oOrder->oxorder__oxpaid = new oxField(oxRegistry::get("oxUtilsDate")->formatDBDate(date("Y-m-d H:i:s"), true));
                $oOrder->save();
                oxRegistry::getSession()->setVariable('girocheckout_disable_article_check', '1');
                $oOrder->sendGiroCheckoutOrderByEmail();
                oxRegistry::getSession()->deleteVariable('girocheckout_disable_article_check');

                $notify->sendOkStatus();
                $notify->setNotifyResponseParam('Result', 'OK');
                $notify->setNotifyResponseParam('ErrorMessage', '');
                $notify->setNotifyResponseParam('MailSent', '');
                $notify->setNotifyResponseParam('OrderId', $iOrderId);
                $notify->setNotifyResponseParam('CustomerId', $iUserId);
                echo $notify->getNotifyResponseStringJson();
            }
        } catch (Exception $e) {
            $notify->sendBadRequestStatus();
            $notify->setNotifyResponseParam('Result', 'ERROR');
            $notify->setNotifyResponseParam('ErrorMessage', 'Exception en notify: ' . $e->getMessage());
            $notify->setNotifyResponseParam('MailSent', '');
            $notify->setNotifyResponseParam('OrderId', $iOrderId);
            $notify->setNotifyResponseParam('CustomerId', $iUserId);
            echo $notify->getNotifyResponseStringJson();
            exit;
        }

        header("HTTP/1.1 200 OK");
        exit;
    }

    /**
     * handles GiroCheckout redirect action.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * 
     */
    public function processGiroCheckoutRedirect() {
        $pm = $_GET['pm'];

        if (empty($_GET['pm'])) {
            exit;
        }

        $lang = strtoupper(oxRegistry::getLang()->getLanguageAbbr());

        $sess_challenge = $_GET['sess_challenge'];

        /** @var girocheckout__oxorder $oOrder */
        $oOrder = oxNew('oxorder');
        $oOrder->load($sess_challenge);
        try {
            $notify = null;
            if ($pm == "gc_creditcard") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('creditCardTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_giropay") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('giropayTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_ideal") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('idealPayment');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_IDEAL_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_eps") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('epsTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_EPS_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_paydirekt") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('paydirektTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_SECRET'));
                $notify->parseNotification($_GET);
            } else if ($pm == "gc_sofortuw") {

                //Get the notification
                $notify = new GiroCheckout_SDK_Notify('sofortuwTransaction');
                $notify->setSecret(oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_SECRET'));
                $notify->parseNotification($_GET);
            }

            if (!$notify->paymentSuccessful()) {
                
                $sErrorMsg= $notify->getResponseMessage($notify->getResponseParam('gcResultPayment'), $lang);
                if($pm == "gc_giropay"){
                    $iReturnCodeAVS = $notify->getResponseParam('gcResultAVS');
                    if (!$notify->avsSuccessful() && $iReturnCodeAVS != "4900" && ($iReturnCodeAVS == "4020" || $iReturnCodeAVS == "4021" || $iReturnCodeAVS == "4022")) {
                        $paymentAVSMsg = $notify->getResponseMessage($iReturnCodeAVS, $lang);
                        if (!empty($paymentAVSMsg)) {
                            $sErrorMsg .= " (" . $paymentAVSMsg . ")";
                        }
                    }    
                }
                oxRegistry::getSession()->setVariable(
                        'sess_challenge', oxUtilsObject::getInstance()->generateUID()
                ); // <-- forces new order creation
                oxRegistry::getSession()->setVariable('_girocheckout_payment_error', $sErrorMsg);
                oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=payment');
            } else {
                oxRegistry::getSession()->deleteVariable('girocheckout_ideal_issuers');
                oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=thankyou');
            }
        } catch (Exception $e) {
            oxRegistry::getSession()->setVariable(
                    'sess_challenge', oxUtilsObject::getInstance()->generateUID()
            ); // <-- forces new order creation
            oxRegistry::getSession()->setVariable('_girocheckout_payment_error', GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang));
            oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=payment');
        }
    }

    /**
     * Get the payment source id parameter.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return int
     */
    public function getGcSource() {
        /** @var oxModule $modul */
        $modul = oxNew('oxModule');
        $modul->load('girocheckout');

        return "Oxid " . oxRegistry::getConfig()->getVersion() . ";Oxid Plugin " . $modul->getInfo('version');
    }

    /**
     * Get the payment purpose.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return int
     */
    public function getPurpose($purpose, $oOrder, $oUser) {
        $strPurpose = $purpose;
        $strLastName = "";
        $strFirstName = "";
        $strShopName = "";

        try {

            $oShop = oxNew("oxshop");
            $oShop->load($this->getConfig()->getShopId());

            $iCustomerNr = $oUser->oxuser__oxcustnr->value;
            $strLastName = $oOrder->oxorder__oxbilllname->value;
            $strFirstName = $oOrder->oxorder__oxbillfname->value;
            $strName = $strFirstName . ", " . $strFirstName;

            $iOrderId = $oOrder->oxorder__oxordernr->value;

            $strShopName = $oShop->oxshops__oxname->getRawValue();

            if (empty($strPurpose)) {
                $strPurpose = "{ORDERID}, {SHOPNAME}";
            }

            $strPurpose = str_replace("{ORDERID}", $iOrderId, $strPurpose);
            $strPurpose = str_replace("{CUSTOMERID}", $iCustomerNr, $strPurpose);
            $strPurpose = str_replace("{SHOPNAME}", $strShopName, $strPurpose);
            $strPurpose = str_replace("{CUSTOMERNAME}", $strName, $strPurpose);
            $strPurpose = str_replace("{CUSTOMERFIRSTNAME}", $strFirstName, $strPurpose);
            $strPurpose = str_replace("{CUSTOMERLASTNAME}", $strLastName, $strPurpose);
        } catch (Exception $e) {
            throw new Exception("Exception in getPurpose(): " . $exception->getMessage());
        }

        if (empty($strPurpose)) {
            if (!empty($strFirstName) || !empty($strLastName)) {
                $strPurpose = "Kunde: " . $strFirstName . " " . $strLastName;
            } else if (!empty($strShopName)) {
                $strPurpose = "Bestellung: " . $strShopName;
            } else {
                $strPurpose = "Bestellung: Shopware";
            }
        }

        return substr($strPurpose, 0, 27);
    }

    /**
     * Get shipping information for paydirekt.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return array
     * 
     */
    public function getShippingData($oOrder) {

        $aReturn = [];
        try {

            if (!empty($oOrder->oxorder__oxdellname->value)) {
                $aReturn["shippingAddresseFirstName"] = $oOrder->oxorder__oxdelfname->value;
                $aReturn["shippingAddresseLastName"] = $oOrder->oxorder__oxdellname->value;
                $aReturn["shippingCompany"] = $oOrder->oxorder__oxdelcompany->value;
                $aReturn["shippingAdditionalAddressInformation"] = $oOrder->oxorder__oxdeladdinfo->value;

                $aReturn["shippingStreet"] = $oOrder->oxorder__oxdelstreet->value . " " . $oOrder->oxorder__oxdelstreetnr->value;
                $aReturn["shippingZipCode"] = $oOrder->oxorder__oxdelzip->value;
                $aReturn["shippingCity"] = $oOrder->oxorder__oxdelcity->value;

                $oCountry = oxNew("oxcountry");
                $oCountry->load($oOrder->oxorder__oxdelcountryid->value);

                $aReturn["shippingCountryIso"] = $oCountry->oxcountry__oxisoalpha2->value;
            } else if ($oOrder->oxorder__oxbilllname->value) {
                $aReturn["shippingAddresseFirstName"] = $oOrder->oxorder__oxbillfname->value;
                $aReturn["shippingAddresseLastName"] = $oOrder->oxorder__oxbilllname->value;
                $aReturn["shippingCompany"] = $oOrder->oxorder__oxbillcompany->value;
                $aReturn["shippingAdditionalAddressInformation"] = $oOrder->oxorder__oxbilladdinfo->value;

                $aReturn["shippingStreet"] = $oOrder->oxorder__oxbillstreet->value . " " . $oOrder->oxorder__oxbillstreetnr->value;
                $aReturn["shippingZipCode"] = $oOrder->oxorder__oxbillzip->value;
                $aReturn["shippingCity"] = $oOrder->oxorder__oxbillcity->value;

                $oCountry = oxNew("oxcountry");
                $oCountry->load($oOrder->oxorder__oxbillcountryid->value);

                $aReturn["shippingCountryIso"] = $oCountry->oxcountry__oxisoalpha2->value;
            }

            $aReturn["shippingAmount"] = $oOrder->getOrderDeliveryPrice()->getBruttoPrice();
        } catch (Exception $exception) {
            throw new Exception("Exception in getShippingData(): " . $exception->getMessage());
        }

        return $aReturn;
    }

}
