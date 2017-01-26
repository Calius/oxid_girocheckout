<?php
  /**
 * Payment Modul fur girosolution.de.
 *
 * @version   4.1.4
 * @author    OCS
 * @copyright 2011-2016 GiroSolution AG
 * @link      http://www.girosolution.de
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 */
$aLang = array(
    'charset' => 'utf-8',
    // GiroCheckout module
    'girocheckout' => 'GiroCheckout',
    'SHOP_MODULE_GROUP_girocheckout_giropay_settings' => 'giropay Settings',
    'SHOP_MODULE_GROUP_girocheckout_creditcard_settings' => 'Credit Card Settings',
    'SHOP_MODULE_GROUP_girocheckout_directdebit_settings' => 'Direct Debit Settings',
    'SHOP_MODULE_GROUP_girocheckout_ideal_settings' => 'iDEAL Settings',
    'SHOP_MODULE_GROUP_girocheckout_eps_settings' => 'eps Settings',
    'SHOP_MODULE_GROUP_girocheckout_paydirekt_settings' => 'Paydirekt Settings',
    'SHOP_MODULE_GROUP_girocheckout_sofortuw_settings' => 'SOFORT Überweisung Settings',
    'SHOP_MODULE_GC_SETTINGS' => 'Settings',
    'SHOP_MODULE_GC_CREDITCARD_MERCHANTID' => 'Merchant Id',
    'SHOP_MODULE_GC_CREDITCARD_PROJECTID' => 'Project Id',
    'SHOP_MODULE_GC_CREDITCARD_SECRET' => 'Password',
    'SHOP_MODULE_GC_CREDITCARD_PURPOSE' => 'Purpose',
    'HELP_SHOP_MODULE_GC_CREDITCARD_PURPOSE' => 'You can define your own purpose using this placeholders:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Order ID</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Customer ID</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Customer Name</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Customer First Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Customer Last Name</li>
                                                                    </ul>
                                                                    For example: If your purpose is "Best. {ORDERID}, {SHOPNAME}" then the submitted purpose must be "Best. 55342, TestShop"
                                                                    The maximum length of the purpose is 27 characters.',
    'SHOP_MODULE_GC_CREDITCARD_SHOWVISAMC' => 'Visa/Mastercard',
    'SHOP_MODULE_GC_CREDITCARD_SHOWAMEX' => 'AMEX',
    'SHOP_MODULE_GC_CREDITCARD_SHOWJCB' => 'JCB',
    'SHOP_MODULE_GC_GIROPAY_MERCHANTID' => 'Merchant Id',
    'SHOP_MODULE_GC_GIROPAY_PROJECTID' => 'Project Id',
    'SHOP_MODULE_GC_GIROPAY_SECRET' => 'Password',
    'SHOP_MODULE_GC_GIROPAY_PURPOSE' => 'Purpose',
    'HELP_SHOP_MODULE_GC_GIROPAY_PURPOSE' => 'You can define your own purpose using this placeholders:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Order ID</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Customer ID</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Customer Name</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Customer First Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Customer Last Name</li>
                                                                    </ul>
                                                                    For example: If your purpose is "Best. {ORDERID}, {SHOPNAME}" then the submitted purpose must be "Best. 55342, TestShop"
                                                                    The maximum length of the purpose is 27 characters.',
    'SHOP_MODULE_GC_DIRECTDEBIT_MERCHANTID' => 'Merchant Id',
    'SHOP_MODULE_GC_DIRECTDEBIT_PROJECTID' => 'Project Id',
    'SHOP_MODULE_GC_DIRECTDEBIT_SECRET' => 'Password',
    'SHOP_MODULE_GC_DIRECTDEBIT_PURPOSE' => 'Purpose',
    'HELP_SHOP_MODULE_GC_DIRECTDEBIT_PURPOSE' => 'You can define your own purpose using this placeholders:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Order ID</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Customer ID</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Customer Name</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Customer First Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Customer Last Name</li>
                                                                    </ul>
                                                                    For example: If your purpose is "Best. {ORDERID}, {SHOPNAME}" then the submitted purpose must be "Best. 55342, TestShop"
                                                                    The maximum length of the purpose is 27 characters.',
    'SHOP_MODULE_GC_DIRECTDEBIT_SHOWBANK' => 'Show Bank Account/ Bank Code input field',
    'SHOP_MODULE_GC_IDEAL_MERCHANTID' => 'Merchant Id',
    'SHOP_MODULE_GC_IDEAL_PROJECTID' => 'Project Id',
    'SHOP_MODULE_GC_IDEAL_SECRET' => 'Password',
    'SHOP_MODULE_GC_IDEAL_PURPOSE' => 'Purpose',
    'HELP_SHOP_MODULE_GC_IDEAL_PURPOSE' => 'You can define your own purpose using this placeholders:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Order ID</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Customer ID</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Customer Name</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Customer First Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Customer Last Name</li>
                                                                    </ul>
                                                                    For example: If your purpose is "Best. {ORDERID}, {SHOPNAME}" then the submitted purpose must be "Best. 55342, TestShop"
                                                                    The maximum length of the purpose is 27 characters.',
    'SHOP_MODULE_GC_EPS_MERCHANTID' => 'Merchant Id',
    'SHOP_MODULE_GC_EPS_PROJECTID' => 'Project Id',
    'SHOP_MODULE_GC_EPS_SECRET' => 'Password',
    'SHOP_MODULE_GC_EPS_PURPOSE' => 'Purpose',
    'HELP_SHOP_MODULE_GC_EPS_PURPOSE' => 'You can define your own purpose using this placeholders:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Order ID</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Customer ID</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Customer Name</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Customer First Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Customer Last Name</li>
                                                                    </ul>
                                                                    For example: If your purpose is "Best. {ORDERID}, {SHOPNAME}" then the submitted purpose must be "Best. 55342, TestShop"
                                                                    The maximum length of the purpose is 27 characters.',
    'SHOP_MODULE_GC_PAYDIREKT_MERCHANTID' => 'Merchant Id',
    'SHOP_MODULE_GC_PAYDIREKT_PROJECTID' => 'Project Id',
    'SHOP_MODULE_GC_PAYDIREKT_SECRET' => 'Password',
    'SHOP_MODULE_GC_PAYDIREKT_PURPOSE' => 'Purpose',
    'HELP_SHOP_MODULE_GC_PAYDIREKT_PURPOSE' => 'You can define your own purpose using this placeholders:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Order ID</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Customer ID</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Customer Name</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Customer First Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Customer Last Name</li>
                                                                    </ul>
                                                                    For example: If your purpose is "Best. {ORDERID}, {SHOPNAME}" then the submitted purpose must be "Best. 55342, TestShop"
                                                                    The maximum length of the purpose is 27 characters.',
    'SHOP_MODULE_GC_SOFORTUW_MERCHANTID' => 'Merchant Id',
    'SHOP_MODULE_GC_SOFORTUW_PROJECTID' => 'Project Id',
    'SHOP_MODULE_GC_SOFORTUW_SECRET' => 'Password',
    'SHOP_MODULE_GC_SOFORTUW_PURPOSE' => 'Purpose',
    'HELP_SHOP_MODULE_GC_SOFORTUW_PURPOSE' => 'You can define your own purpose using this placeholders:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Order ID</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Customer ID</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Customer Name</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Customer First Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Customer Last Name</li>
                                                                    </ul>
                                                                    For example: If your purpose is "Best. {ORDERID}, {SHOPNAME}" then the submitted purpose must be "Best. 55342, TestShop"
                                                                    The maximum length of the purpose is 27 characters.',
);
