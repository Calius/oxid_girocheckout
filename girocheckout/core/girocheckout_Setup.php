<?php

/**
 * This class is used to install girocheckout module
 *
 */
class girocheckout_Setup extends oxAdminView {

    private $oxPayment;

    public function __construct() {
        $this->oxPayment = oxNew("oxpayment");
    }

    /**
     * Install tables if not exists
     */
    public function girocheckout__install() {

        $modul = oxNew('oxModule');
        $modul->load('girocheckout');

        $this->deleteOldConfigVariables();

        if (!$this->isTablesInstalled()) {

            oxDb::getDb()->Execute("CREATE TABLE IF NOT EXISTS `giroconnect__order_number_reservations` (" .
                    "`OXID` CHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ," .
                    " UNIQUE (`OXID`)" .
                    " ) ENGINE = MYISAM ;");
        }

        $aPaymentNotInstalled = $this->isPaymentInstalled();
        if (!empty($aPaymentNotInstalled)) {
            $this->installPayments($aPaymentNotInstalled);
        }

        $this->updateGiroCheckoutPaymentNames();

        //With new update, the oxid shop will automatically create the blocks based on the metadata file
//    if (!$this->areBlocksSet()) {
//      $this->updateBlocks();
//    }
//     INSERT girocheckout status column
        $this->addgirocheckoutStatusColumnInOrderTable();
        $this->addgirocheckoutTransactionIdColumnInOrderTable();
    }

    protected function updateGiroCheckoutPaymentNames() {
        
        $db = oxDb::getDb();
        foreach ($this->paymentMethods() as $pm) {
            $name_de = $pm["de"];
            $name_en = $pm["en"];
            $name = $pm["name"];
            $update = "UPDATE oxpayments SET OXDESC = " . $db->quote($name_de . ' (GiroCheckout)') .", OXDESC_1 = " . $db->quote($name_en . ' (GiroCheckout)') ." WHERE OXID = "  . $db->quote($name);
            $db->Execute($update);
        }
    }

    protected function deleteOldConfigVariables() {

        $shopConfig = oxRegistry::getConfig();
        $sShopId = $shopConfig->getBaseShopId();

        $oDb = oxDb::getDb();

        // delete existent settings
        $sSql = "DELETE FROM `oxconfig` WHERE `oxshopid`  = " . $oDb->quote($sShopId) . " AND `oxmodule` = " . $oDb->quote('module:giroconnect') . " ";

        $oDb->Execute($sSql);

        // delete existent settings
        $sSql = "DELETE FROM `oxconfigdisplay` WHERE `oxcfgmodule` = " . $oDb->quote('module:giroconnect') . " ";

        $oDb->Execute($sSql);
    }

    //check if tables created.
    protected function isTablesInstalled() {

        $giroconnect__order_number_reservations = oxDb::getDb(oxDB::FETCH_MODE_ASSOC)->getRow('CHECK TABLE `giroconnect__order_number_reservations`');
        return $giroconnect__order_number_reservations['Msg_text'] === "OK";
    }

    /**
     * TODO
     *
     * @return array of payment methods in english and deutsch.
     *   TODO
     */
    protected function paymentMethods() {
        return array(
            array('de' => "giropay",
                'en' => "giropay",
                'name' => "gc_giropay",
            ),
            array('de' => "Lastschrift",
                'en' => "Direct Debit",
                'name' => "gc_directdebit",
            ),
            array('de' => "Kreditkarte",
                'en' => "Credit Card",
                'name' => "gc_creditcard",
            ),
            array('de' => "iDEAL",
                'en' => "iDEAL",
                'name' => "gc_ideal",
            ),
            array('de' => "eps",
                'en' => "eps",
                'name' => "gc_eps",
            ),
            array('de' => "Paydirekt",
                'en' => "Paydirekt",
                'name' => "gc_paydirekt",
            ),
            array('de' => "SOFORT Überweisung",
                'en' => "SOFORT Überweisung",
                'name' => "gc_sofortuw",
            ),
        );
    }

    //insert rows of payment methods are created in oxPayments table
    protected function installPayments($p_aPaymentNotInstalled) {
        $db = oxDb::getDb();
        foreach ($this->paymentMethods() as $pm) {
            if (!empty($p_aPaymentNotInstalled) && in_array($pm["name"], $p_aPaymentNotInstalled)) {
                $name_de = $pm["de"];
                $name_en = $pm["en"];
                $name = $pm["name"];

                $insert = "INSERT INTO oxpayments (OXID, OXACTIVE, OXDESC, OXADDSUM, OXADDSUMTYPE, OXADDSUMRULES, OXFROMBONI, OXFROMAMOUNT, ".
                  "OXTOAMOUNT, OXVALDESC, OXCHECKED, OXDESC_1, OXVALDESC_1, OXDESC_2, OXVALDESC_2, OXDESC_3, OXVALDESC_3, OXLONGDESC, ".
                  "OXLONGDESC_1, OXLONGDESC_2, OXLONGDESC_3, OXSORT, OXTSPAYMENTID) VALUES(" .
                  $db->quote($name) . ", 1, " . $db->quote($name_de . ' (GiroCheckout)') . ", 0, 'abs', 15, 0, 0, 1000000, '', 0, " .
                  $db->quote($name_en . ' (GiroCheckout)') . ", '', '', '', '', '', '', '', '', '', 0, '')";
                $db->Execute($insert);
            }
        }
    }

    //check if rows of payment methods are created in oxPayments table
    protected function isPaymentInstalled() {
        $aPaymentNotInstalled = array();
        foreach ($this->paymentMethods() as $pm) {
            $this->oxPayment->oxpayments__oxid->value = null;
            $this->oxPayment->load($pm["name"]);

            $isPMNotInstalled = is_null($this->oxPayment->oxpayments__oxid->value);
            if ($isPMNotInstalled == true) {
                $aPaymentNotInstalled[] = $pm["name"];
            }
        }
        return $aPaymentNotInstalled;
    }

    /**
     * Are blocks set in oxtplblocks table
     * @return bool
     */
    protected function areBlocksSet() {
        $girocheckout__select_payment = $this->queryForBlock('girocheckout__select_payment');
        $girocheckout__payment_errors = $this->queryForBlock('girocheckout__payment_errors');
        return $girocheckout__select_payment && $girocheckout__payment_errors;
    }

    /**
     * Db query for oxBlockname specified, returns true if block in table
     * oxtplblocks present
     * @param  string $oxBlockname
     * @return bool
     */
    protected function queryForBlock($oxBlockname) {
        $shopId = $this->getConfig()->getShopId();
        $db = oxDb::getDb();
        $result = $db->getOne(
                "SELECT 1 FROM oxtplblocks"
                . " WHERE oxmodule = 'girocheckout'"
                . " AND oxshopid = " . $db->quote($shopId)
                . " AND oxid = " . $db->quote($oxBlockname)
                . " LIMIT 1"
        );

        return (bool) $result;
    }

    /**
     * Update new girocheckout blocks if missing
     */
    protected function updateBlocks() {
        if (!$this->queryForBlock('girocheckout__select_payment')) {
            $this->insertBlock(
                    'girocheckout__select_payment', 'select_payment', 'page/checkout/payment.tpl', 'out/blocks/page/checkout/payment/select_payment.tpl'
            );
        }

        if (!$this->queryForBlock('girocheckout__payment_errors')) {
            $this->insertBlock(
                    'girocheckout__payment_errors', 'checkout_payment_errors', 'page/checkout/payment.tpl', 'out/blocks/page/checkout/payment/checkout_payment_errors.tpl'
            );
        }
    }

    /**
     * Insert entry for girocheckout to oxtplblocks table
     * @param  string $oxId
     * @param  string $oxBlockname
     * @param  string $oxTemplate
     * @param  string $oxFile
     */
    protected function insertBlock($oxId, $oxBlockname, $oxTemplate, $oxFile) {
        $db = oxDb::getDb();
        $shopId = $this->getConfig()->getShopId();
        $sql = "INSERT INTO `oxtplblocks` (
                    `OXID`, `OXACTIVE`, `OXSHOPID`, `OXTEMPLATE`,
                    `OXBLOCKNAME`, `OXPOS`, `OXFILE`, `OXMODULE`
                ) VALUES (
                    " . $db->quote($oxId) . ",
                    1,
                    " . $db->quote($shopId) . ",
                    " . $db->quote($oxTemplate) . ",
                    " . $db->quote($oxBlockname) . ",
                    '1',
                    " . $db->quote($oxFile) . ", 'girocheckout'
                )
        ";

        // @TODO add exception handling
        $db->execute($sql);
    }

    function addGiroCheckoutStatusColumnInOrderTable() {
        $result = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->Execute("SHOW COLUMNS FROM `oxorder` LIKE '%giroconnect__status%'");
        // if column doesn't exists
        if ($result->RecordCount() == 0) {
            $sql = "ALTER TABLE `oxorder` ADD `giroconnect__status` smallint NULL";
            oxDb::getDb()->Execute($sql);

            /** @var oxDbMetaDataHandler $oxDbMetaDataHandler */
            $oxDbMetaDataHandler = oxnew('oxDbMetaDataHandler');
            $oxDbMetaDataHandler->updateViews();

            #oxDb::getInstance()->updateViews();
        }
    }

    function addGiroCheckoutTransactionIdColumnInOrderTable() {
        $result = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->Execute("SHOW COLUMNS FROM `oxorder` LIKE '%giroconnect__transaction_id%'");
        // if column doesn't exists
        if ($result->RecordCount() == 0) {
            $sql = "ALTER TABLE `oxorder` ADD `giroconnect__transaction_id` varchar(32) NULL";
            oxDb::getDb()->Execute($sql);

            /** @var oxDbMetaDataHandler $oxDbMetaDataHandler */
            $oxDbMetaDataHandler = oxnew('oxDbMetaDataHandler');
            $oxDbMetaDataHandler->updateViews();

            #oxDb::getInstance()->updateViews();
        }
    }

}
