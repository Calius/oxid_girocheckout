<?php

require_once getShopBasePath() . 'modules/girocheckout/lib/GiroCheckout_SDK/GiroCheckout_SDK.php';

/**
 * Class girocheckout__payment
 */
class girocheckout__payment extends girocheckout__payment_parent {

    public $_girocheckout_payment_error;

    /**
     * return error message to display.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return string
     * 
     */
    public function getGirocheckoutPaymentError() {
        if (oxRegistry::getSession()->hasVariable('_girocheckout_payment_error')) {
            $this->_girocheckout_payment_error = oxRegistry::getSession()->getVariable('_girocheckout_payment_error');
            oxRegistry::getSession()->deleteVariable('_girocheckout_payment_error');
        }

        return $this->_girocheckout_payment_error;
    }

    /**
     * check if the error message is from girocheckout.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return boolean
     * 
     */
    public function isGirocheckoutPaymentError() {
        if (!empty($this->_girocheckout_payment_error) || oxRegistry::getSession()->hasVariable('_girocheckout_payment_error')) {
            return true;
        }

        return false;
    }

    /**
     * validate payment input data from customer.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return mixed
     * 
     */
    public function validatePayment() {
        $paymentId = oxRegistry::getConfig()->getRequestParameter('paymentid');
        $parentResult = parent::validatePayment();
        $aDynvalue = oxRegistry::getConfig()->getRequestParameter('dynvalue');
        $sourceParam = $this->getGcSource();
        $result['msg'] = '';

        // get current language
        $lang = strtoupper(oxRegistry::getLang()->getLanguageAbbr());

        if ($paymentId == "gc_creditcard") {
            if (oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SECRET') == '') {

                $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
            }
        }

        if ($paymentId == "gc_directdebit") {

            if (oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_SECRET') == '') {

                $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
            } else {

                // check values
                $sDdatabankcheck = $aDynvalue['gc_directdebit_cdatabankcheck'];
                $sIban = $aDynvalue['gc_directdebit_iban'];
                $sBankcode = $aDynvalue['gc_directdebit_bankcode'];
                $sAccountNumber = $aDynvalue['gc_directdebit_account'];
                $sAccountHolder = $aDynvalue['gc_directdebit_accountholder'];

                if (!empty($sAccountHolder)) {
                    if (empty($aDynvalue['gc_directdebit_cdatabankcheck']) || $aDynvalue['gc_directdebit_cdatabankcheck'] == 'rbIbanDirectdebit' || !oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_SHOWBANK')) {
                        if (empty($sIban)) {
                            $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5027, $lang);
                        }
                    } else {
                        if (empty($sBankcode)) {
                            $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5024, $lang);
                        } else if (empty($sAccountNumber)) {
                            $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5025, $lang);
                        }
                    }
                } else {
                    $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5085, $lang);
                }
            }
        }

        if ($paymentId == "gc_eps") {

            if (oxRegistry::getConfig()->getConfigParam('GC_EPS_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_EPS_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_EPS_SECRET') == '') {

                $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
            } else {

                // check bankcode
                $sEpsBic = $aDynvalue["gc_eps_bic"];

                if (empty($sEpsBic)) {
                    $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5026, $lang);
                } else {

                    //Check if the bank provides giropay from BIC.
                    try {
                        $request = new GiroCheckout_SDK_Request('epsBankstatus');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_EPS_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_EPS_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_EPS_PROJECTID'))
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('bic', $sEpsBic)
                                ->submit();

                        if (!$request->requestHasSucceeded()) {
                            $iReturnCode = $request->getResponseParam('rc');
                            $result['msg'] = $request->getResponseMessage($iReturnCode, $lang);
                        }
                    } catch (Exception $e) {
                        $result["msg"] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
                    }
                }
            }
        }

        if ($paymentId == "gc_giropay") {

            if (oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_SECRET') == '') {

                $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
            } else {

                // check bankcode
                $sGiropayBic = $aDynvalue["gc_giropay_bic"];

                if (empty($sGiropayBic)) {
                    $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5026, $lang);
                } else {

                    //Check if the bank provides giropay from BIC.
                    try {
                        $request = new GiroCheckout_SDK_Request('giropayBankstatus');
                        $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_SECRET'));
                        $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_MERCHANTID'))
                                ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_PROJECTID'))
                                ->addParam('sourceId', $sourceParam)
                                ->addParam('bic', $sGiropayBic)
                                ->submit();

                        if (!$request->requestHasSucceeded()) {
                            $iReturnCode = $request->getResponseParam('rc');
                            $result['msg'] = $request->getResponseMessage($iReturnCode, $lang);
                        }
                    } catch (Exception $e) {
                        $result["msg"] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
                    }
                }
            }
        }

        if ($paymentId == "gc_ideal") {

            if (oxRegistry::getConfig()->getConfigParam('GC_IDEAL_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_IDEAL_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_IDEAL_SECRET') == '') {

                $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
            } else {

                // check values
                $sIssuer = $aDynvalue['gc_ideal_issuer'];

                if (empty($sIssuer)) {
                    $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5054, $lang);
                }
            }
        }

        if ($paymentId == "gc_paydirekt") {
            if (oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_SECRET') == '') {

                $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
            }
        }

        if ($paymentId == "gc_sofortuw") {
            if (oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_SECRET') == '') {

                $result['msg'] = GiroCheckout_SDK_ResponseCode_helper::getMessage(5100, $lang);
            }
        }

        if (!empty($result['msg'])) {
            $this->_sPaymentError = 'girocheckout';
            $this->_girocheckout_payment_error = $result['msg'];

            return;
        }

        return $parentResult;
    }

    /**
     * return issuers array for iDEAL payment method.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return array
     * 
     */
    public function getIssuers() {
        // load settings
        $aIssuers = array();

        $sourceParam = $this->getGcSource();

        if (oxRegistry::getConfig()->getConfigParam('GC_IDEAL_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_IDEAL_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_IDEAL_SECRET') == '') {
            
        } else {
            //Sends request to Girocheckout to get the list of the issuers.
            try {
                $aIssuers = oxRegistry::getSession()->getVariable('girocheckout_ideal_issuers');

                if (empty($aIssuers)) {

                    $request = new GiroCheckout_SDK_Request('idealIssuerList');
                    $request->setSecret(oxRegistry::getConfig()->getConfigParam('GC_IDEAL_SECRET'));
                    $request->addParam('merchantId', oxRegistry::getConfig()->getConfigParam('GC_IDEAL_MERCHANTID'))
                            ->addParam('projectId', oxRegistry::getConfig()->getConfigParam('GC_IDEAL_PROJECTID'))
                            ->addParam('sourceId', $sourceParam)
                            ->submit();

                    if ($request->requestHasSucceeded()) {
                        $aOriIssuers = $request->getResponseParam('issuer');

                        foreach ($aOriIssuers as $k => $v) {
                            $entry = array(
                                'value' => $k,
                                'text' => $v,
                            );
                            $aIssuers[] = $entry;
                        }

                        oxRegistry::getSession()->setVariable('girocheckout_ideal_issuers', $aIssuers);
                    }
                }
            } catch (Exception $e) {
                
            }
        }

        return $aIssuers;
    }

    /**
     * return the logo to display for credit card payment method.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return string
     * 
     */
    public function getGiroCheckoutCreditCardLogo() {
        // set special payment logo
        $visa_msc = oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SHOWVISAMC');
        $amex = oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SHOWAMEX');
        $jcb = oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SHOWJCB');
        if (!$visa_msc && !$amex && !$jcb) {
            $visa_msc = TRUE;
        }
        $sCreditCardLogo = GiroCheckout_SDK_Tools::getCreditCardLogoName($visa_msc, $amex, $jcb, false, 40);

        return $sCreditCardLogo;
    }

    /**
     * Check if switch for direct debit method is enabled in config.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return boolean
     * 
     */
    public function showGiroCheckoutDataBankConfig() {
        $bShowDatabank = oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_SHOWBANK');

        return $bShowDatabank;
    }

    /**
     * return active currency name.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return string
     * 
     */
    public function getActiveCurrencyName() {
        $oCur = $this->getConfig()->getActShopCurrencyObject();
        $sCur = $oCur->name;

        return $sCur;
    }

    /**
     * check if credentials are set in config for GiroCheckout payment methods.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return boolean
     * 
     */
    public function isSettingsSet($paymentId) {
        $settingsAreSet = true;

        if ($paymentId == "gc_giropay") {

            if (oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_GIROPAY_SECRET') == '') {
                $settingsAreSet = false;
            }
        }
        if ($paymentId == "gc_directdebit") {

            if (oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_DIRECTDEBIT_SECRET') == '') {
                $settingsAreSet = false;
            }
        }
        if ($paymentId == "gc_ideal") {

            if (oxRegistry::getConfig()->getConfigParam('GC_IDEAL_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_IDEAL_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_IDEAL_SECRET') == '') {
                $settingsAreSet = false;
            }
        }
        if ($paymentId == "gc_creditcard") {

            if (oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_CREDITCARD_SECRET') == '') {
                $settingsAreSet = false;
            }
        }
        if ($paymentId == "gc_eps") {

            if (oxRegistry::getConfig()->getConfigParam('GC_EPS_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_EPS_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_EPS_SECRET') == '') {
                $settingsAreSet = false;
            }
        }

        if ($paymentId == "gc_paydirekt") {

            if (oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_PAYDIREKT_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_EPS_SECRET') == '') {
                $settingsAreSet = false;
            }
        }

        if ($paymentId == "gc_sofortuw") {

            if (oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_MERCHANTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_PROJECTID') == '' || oxRegistry::getConfig()->getConfigParam('GC_SOFORTUW_SECRET') == '') {
                $settingsAreSet = false;
            }
        }

        return $settingsAreSet;
    }

    /**
     * return source id param.
     *
     * @author GiroSolution AG
     * @package GiroCheckout
     * @copyright Copyright (c) 2016, GiroSolution AG
     * @return string
     * 
     */
    public function getGcSource() {
        /** @var oxModule $modul */
        $modul = oxNew('oxModule');
        $modul->load('girocheckout');

        return "Oxid " . oxRegistry::getConfig()->getVersion() . ";Oxid Plugin " . $modul->getInfo('version');
    }

}
