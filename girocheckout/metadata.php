<?php

/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @package   main
 * @copyright (C) OXID eSales AG 2003-2012
 * @version OXID eShop CE
 * @version   SVN: $Id: theme.php 25466 2010-02-01 14:12:07Z alfonsas $
 */
/**
 * Module information
 */
$sMetadataVersion = '1.2';
$aModule = array(
    'id' => 'girocheckout',
    'title' => 'GiroCheckout',
    'description' => 'Module to integrate all payment methods from GiroSolution AG.',
    'version' => '4.1.4',
    'thumbnail' => 'images/Logo.jpg',
    'author' => 'GiroSolution AG',
    'email' => 'support@girosolution.de',
    'url' => 'https://www.girosolution.de',
    'extend' => array(
        'payment' => 'girocheckout/application/controllers/girocheckout__payment',
        'order' => 'girocheckout/application/controllers/girocheckout__order',
        'oxorder' => 'girocheckout/application/models/girocheckout__oxorder',
        'oxbasket' => 'girocheckout/application/models/girocheckout__oxbasket',
    ),
    'files' => array(
        'girocheckout_Setup' => 'girocheckout/core/girocheckout_Setup.php',
        'girocheckout_Events' => 'girocheckout/core/girocheckout_Events.php',
        //Model
        'girocheckout__order_number_reservation' => 'girocheckout/application/models/girocheckout__order_number_reservation.php',
    ),
    'blocks' => array(
        array('template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => 'views/azure/blocks/page/checkout/payment/select_payment.tpl'
        ),
        array('template' => 'page/checkout/payment.tpl',
            'block' => 'checkout_payment_errors',
            'file' => 'views/azure/blocks/page/checkout/payment/checkout_payment_errors.tpl'
        ),
        array('template' => 'page/checkout/order.tpl',
            'block' => 'checkout_order_main',
            'file' => 'views/azure/blocks/page/checkout/payment/checkout_order_main.tpl'
        ), 
        array('template' => 'page/checkout/payment.tpl',
            'block' => 'mb_select_payment',
            'file' => 'views/mobile/blocks/page/checkout/payment/select_payment.tpl'
        ),
        array('template' => 'page/checkout/payment.tpl',
            'block' => 'mb_select_payment_dropdown',
            'file' => 'views/mobile/blocks/page/checkout/payment/mb_select_payment_dropdown.tpl'
        ),
        array('template' => 'page/checkout/order.tpl',
            'block' => 'checkout_order_main',
            'file' => 'views/mobile/blocks/page/checkout/payment/checkout_order_main.tpl'
        ),
    ),
    'templates' => array(
        'girocheckout_creditcard.tpl' => 'girocheckout/views/azure/tpl/page/checkout/inc/girocheckout_creditcard.tpl',
        'girocheckout_directdebit.tpl' => 'girocheckout/views/azure/tpl/page/checkout/inc/girocheckout_directdebit.tpl',
        'girocheckout_eps.tpl' => 'girocheckout/views/azure/tpl/page/checkout/inc/girocheckout_eps.tpl',
        'girocheckout_giropay.tpl' => 'girocheckout/views/azure/tpl/page/checkout/inc/girocheckout_giropay.tpl',
        'girocheckout_ideal.tpl' => 'girocheckout/views/azure/tpl/page/checkout/inc/girocheckout_ideal.tpl',
        'girocheckout_paydirekt.tpl' => 'girocheckout/views/azure/tpl/page/checkout/inc/girocheckout_paydirekt.tpl',
        'girocheckout_sofortuw.tpl' => 'girocheckout/views/azure/tpl/page/checkout/inc/girocheckout_sofortuw.tpl',
        'mb_girocheckout_creditcard.tpl' => 'girocheckout/views/mobile/tpl/page/checkout/inc/girocheckout_creditcard.tpl',
        'mb_girocheckout_directdebit.tpl' => 'girocheckout/views/mobile/tpl/page/checkout/inc/girocheckout_directdebit.tpl',
        'mb_girocheckout_eps.tpl' => 'girocheckout/views/mobile/tpl/page/checkout/inc/girocheckout_eps.tpl',
        'mb_girocheckout_giropay.tpl' => 'girocheckout/views/mobile/tpl/page/checkout/inc/girocheckout_giropay.tpl',
        'mb_girocheckout_ideal.tpl' => 'girocheckout/views/mobile/tpl/page/checkout/inc/girocheckout_ideal.tpl',
        'mb_girocheckout_paydirekt.tpl' => 'girocheckout/views/mobile/tpl/page/checkout/inc/girocheckout_paydirekt.tpl',
        'mb_girocheckout_sofortuw.tpl' => 'girocheckout/views/mobile/tpl/page/checkout/inc/girocheckout_sofortuw.tpl',
    ),
    'settings' => array(
        array('group' => 'girocheckout_creditcard_settings', 'name' => 'GC_CREDITCARD_MERCHANTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_creditcard_settings', 'name' => 'GC_CREDITCARD_PROJECTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_creditcard_settings', 'name' => 'GC_CREDITCARD_SECRET', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_creditcard_settings', 'name' => 'GC_CREDITCARD_PURPOSE', 'type' => 'str', 'value' => 'Best. {ORDERID}, {SHOPNAME}'),
        array('group' => 'girocheckout_creditcard_settings', 'name' => 'GC_CREDITCARD_SHOWVISAMC', 'type' => 'bool', 'value' => 'true'),
        array('group' => 'girocheckout_creditcard_settings', 'name' => 'GC_CREDITCARD_SHOWAMEX', 'type' => 'bool', 'value' => 'true'),
        array('group' => 'girocheckout_creditcard_settings', 'name' => 'GC_CREDITCARD_SHOWJCB', 'type' => 'bool', 'value' => 'true'),
        array('group' => 'girocheckout_giropay_settings', 'name' => 'GC_GIROPAY_MERCHANTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_giropay_settings', 'name' => 'GC_GIROPAY_PROJECTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_giropay_settings', 'name' => 'GC_GIROPAY_SECRET', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_giropay_settings', 'name' => 'GC_GIROPAY_PURPOSE', 'type' => 'str', 'value' => 'Best. {ORDERID}, {SHOPNAME}'),
        array('group' => 'girocheckout_directdebit_settings', 'name' => 'GC_DIRECTDEBIT_MERCHANTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_directdebit_settings', 'name' => 'GC_DIRECTDEBIT_PROJECTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_directdebit_settings', 'name' => 'GC_DIRECTDEBIT_SECRET', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_directdebit_settings', 'name' => 'GC_DIRECTDEBIT_PURPOSE', 'type' => 'str', 'value' => 'Best. {ORDERID}, {SHOPNAME}'),
        array('group' => 'girocheckout_directdebit_settings', 'name' => 'GC_DIRECTDEBIT_SHOWBANK', 'type' => 'bool', 'value' => 'false'),
        array('group' => 'girocheckout_ideal_settings', 'name' => 'GC_IDEAL_MERCHANTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_ideal_settings', 'name' => 'GC_IDEAL_PROJECTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_ideal_settings', 'name' => 'GC_IDEAL_SECRET', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_ideal_settings', 'name' => 'GC_IDEAL_PURPOSE', 'type' => 'str', 'value' => 'Best. {ORDERID}, {SHOPNAME}'),
        array('group' => 'girocheckout_eps_settings', 'name' => 'GC_EPS_MERCHANTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_eps_settings', 'name' => 'GC_EPS_PROJECTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_eps_settings', 'name' => 'GC_EPS_SECRET', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_eps_settings', 'name' => 'GC_EPS_PURPOSE', 'type' => 'str', 'value' => 'Best. {ORDERID}, {SHOPNAME}'),
        array('group' => 'girocheckout_paydirekt_settings', 'name' => 'GC_PAYDIREKT_MERCHANTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_paydirekt_settings', 'name' => 'GC_PAYDIREKT_PROJECTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_paydirekt_settings', 'name' => 'GC_PAYDIREKT_SECRET', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_paydirekt_settings', 'name' => 'GC_PAYDIREKT_PURPOSE', 'type' => 'str', 'value' => 'Best. {ORDERID}, {SHOPNAME}'),
        array('group' => 'girocheckout_sofortuw_settings', 'name' => 'GC_SOFORTUW_MERCHANTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_sofortuw_settings', 'name' => 'GC_SOFORTUW_PROJECTID', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_sofortuw_settings', 'name' => 'GC_SOFORTUW_SECRET', 'type' => 'str', 'value' => ''),
        array('group' => 'girocheckout_sofortuw_settings', 'name' => 'GC_SOFORTUW_PURPOSE', 'type' => 'str', 'value' => 'Best. {ORDERID}, {SHOPNAME}'),
    ),
    'events' => array(
        'onActivate' => 'girocheckout_Events::onActivate',
        'onDeactivate' => 'girocheckout_Events::onDeactivate'
    ),
);
