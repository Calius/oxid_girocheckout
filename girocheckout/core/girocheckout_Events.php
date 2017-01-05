<?php

/**
 * oxTiramizoo event class fired onActivate module.
 *
 * @package oxTiramizoo
 */
class girocheckout_Events {



    /**
     * Execute girocheckout Module setup
     *
     * @return null
     */
    public static function onActivate() {
        try {
            $girocheckout_Setup = oxRegistry::get('girocheckout_Setup');
            $girocheckout_Setup->girocheckout__install();
            
            
        } catch (oxException $e) {
            // @codeCoverageIgnoreStart
            if (!defined('OXID_PHP_UNIT')) {
                die($e->getMessage());
            }
            // @codeCoverageIgnoreEnd
        }
    }

}
