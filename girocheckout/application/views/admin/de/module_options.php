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
    // GIROCHECKOUT module
    'girocheckout' => 'GiroCheckout',
    'SHOP_MODULE_GROUP_girocheckout_giropay_settings' => 'giropay Einstellungen',
    'SHOP_MODULE_GROUP_girocheckout_creditcard_settings' => 'Kreditkarte Einstellungen',
    'SHOP_MODULE_GROUP_girocheckout_directdebit_settings' => 'Lastschrift Einstellungen',
    'SHOP_MODULE_GROUP_girocheckout_ideal_settings' => 'iDEAL Einstellungen',
    'SHOP_MODULE_GROUP_girocheckout_eps_settings' => 'eps Einstellungen',
    'SHOP_MODULE_GROUP_girocheckout_paydirekt_settings' => 'Paydirekt Einstellungen',
    'SHOP_MODULE_GROUP_girocheckout_sofortuw_settings' => 'SOFORT Überweisung Einstellungen',
    'SHOP_MODULE_GC_SETTINGS' => 'Einstellungen',
    'SHOP_MODULE_GC_CREDITCARD_MERCHANTID' => 'Händler-ID',
    'SHOP_MODULE_GC_CREDITCARD_PROJECTID' => 'Projekt-ID',
    'SHOP_MODULE_GC_CREDITCARD_SECRET' => 'Password',
    'SHOP_MODULE_GC_CREDITCARD_PURPOSE' => 'Verwendungszweck',
    'HELP_SHOP_MODULE_GC_CREDITCARD_PURPOSE' => 'Sie können in Ihrem Verwendungszweck die folgenden Platzhalter verwenden:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Bestellnummer</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Kundennummer</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Kundenname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Kunde Vorname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Kunde Nachname</li>
                                                                    </ul>
                                                                    Beispiel: Wenn Ihr Verwendungszweck "Best. 55342, TestShop" lauten soll, dann müssen Sie folgendes angeben: "Best. {ORDERID}, {SHOPNAME}".
                                                                    Die maximale Länge beträgt 27 Zeichen.',
    'SHOP_MODULE_GC_CREDITCARD_SHOWVISAMC' => 'Visa/Mastercard',
    'SHOP_MODULE_GC_CREDITCARD_SHOWAMEX' => 'AMEX',
    'SHOP_MODULE_GC_CREDITCARD_SHOWJCB' => 'JCB',
    'SHOP_MODULE_GC_GIROPAY_MERCHANTID' => 'Händler-ID',
    'SHOP_MODULE_GC_GIROPAY_PROJECTID' => 'Projekt-ID',
    'SHOP_MODULE_GC_GIROPAY_SECRET' => 'Password',
    'SHOP_MODULE_GC_GIROPAY_PURPOSE' => 'Verwendungszweck',
    'HELP_SHOP_MODULE_GC_GIROPAY_PURPOSE' => 'Sie können in Ihrem Verwendungszweck die folgenden Platzhalter verwenden:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Bestellnummer</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Kundennummer</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Kundenname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Kunde Vorname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Kunde Nachname</li>
                                                                    </ul>
                                                                    Beispiel: Wenn Ihr Verwendungszweck "Best. 55342, TestShop" lauten soll, dann müssen Sie folgendes angeben: "Best. {ORDERID}, {SHOPNAME}".
                                                                    Die maximale Länge beträgt 27 Zeichen.',
    'SHOP_MODULE_GC_DIRECTDEBIT_MERCHANTID' => 'Händler-ID',
    'SHOP_MODULE_GC_DIRECTDEBIT_PROJECTID' => 'Projekt-ID',
    'SHOP_MODULE_GC_DIRECTDEBIT_SECRET' => 'Password',
    'SHOP_MODULE_GC_DIRECTDEBIT_PURPOSE' => 'Verwendungszweck',
    'HELP_SHOP_MODULE_GC_DIRECTDEBIT_PURPOSE' => 'Sie können in Ihrem Verwendungszweck die folgenden Platzhalter verwenden:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Bestellnummer</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Kundennummer</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Kundenname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Kunde Vorname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Kunde Nachname</li>
                                                                    </ul>
                                                                    Beispiel: Wenn Ihr Verwendungszweck "Best. 55342, TestShop" lauten soll, dann müssen Sie folgendes angeben: "Best. {ORDERID}, {SHOPNAME}".
                                                                    Die maximale Länge beträgt 27 Zeichen.',
    'SHOP_MODULE_GC_DIRECTDEBIT_SHOWBANK' => 'Zeige Kontonummer/ BLZ Eingabefeld',
    'SHOP_MODULE_GC_IDEAL_MERCHANTID' => 'Händler-ID',
    'SHOP_MODULE_GC_IDEAL_PROJECTID' => 'Projekt-ID',
    'SHOP_MODULE_GC_IDEAL_SECRET' => 'Password',
    'SHOP_MODULE_GC_IDEAL_PURPOSE' => 'Verwendungszweck',
    'HELP_SHOP_MODULE_GC_IDEAL_PURPOSE' => 'Sie können in Ihrem Verwendungszweck die folgenden Platzhalter verwenden:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Bestellnummer</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Kundennummer</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Kundenname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Kunde Vorname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Kunde Nachname</li>
                                                                    </ul>
                                                                    Beispiel: Wenn Ihr Verwendungszweck "Best. 55342, TestShop" lauten soll, dann müssen Sie folgendes angeben: "Best. {ORDERID}, {SHOPNAME}".
                                                                    Die maximale Länge beträgt 27 Zeichen.',
    'SHOP_MODULE_GC_EPS_MERCHANTID' => 'Händler-ID',
    'SHOP_MODULE_GC_EPS_PROJECTID' => 'Projekt-ID',
    'SHOP_MODULE_GC_EPS_SECRET' => 'Password',
    'SHOP_MODULE_GC_EPS_PURPOSE' => 'Verwendungszweck',
    'HELP_SHOP_MODULE_GC_EPS_PURPOSE' => 'Sie können in Ihrem Verwendungszweck die folgenden Platzhalter verwenden:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Bestellnummer</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Kundennummer</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Kundenname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Kunde Vorname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Kunde Nachname</li>
                                                                    </ul>
                                                                    Beispiel: Wenn Ihr Verwendungszweck "Best. 55342, TestShop" lauten soll, dann müssen Sie folgendes angeben: "Best. {ORDERID}, {SHOPNAME}".
                                                                    Die maximale Länge beträgt 27 Zeichen.',
    'SHOP_MODULE_GC_PAYDIREKT_MERCHANTID' => 'Händler-ID',
    'SHOP_MODULE_GC_PAYDIREKT_PROJECTID' => 'Projekt-ID',
    'SHOP_MODULE_GC_PAYDIREKT_SECRET' => 'Password',
    'SHOP_MODULE_GC_PAYDIREKT_PURPOSE' => 'Verwendungszweck',
    'HELP_SHOP_MODULE_GC_PAYDIREKT_PURPOSE' => 'Sie können in Ihrem Verwendungszweck die folgenden Platzhalter verwenden:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Bestellnummer</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Kundennummer</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Kundenname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Kunde Vorname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Kunde Nachname</li>
                                                                    </ul>
                                                                    Beispiel: Wenn Ihr Verwendungszweck "Best. 55342, TestShop" lauten soll, dann müssen Sie folgendes angeben: "Best. {ORDERID}, {SHOPNAME}".
                                                                    Die maximale Länge beträgt 27 Zeichen.',
    'SHOP_MODULE_GC_SOFORTUW_MERCHANTID' => 'Händler-ID',
    'SHOP_MODULE_GC_SOFORTUW_PROJECTID' => 'Projekt-ID',
    'SHOP_MODULE_GC_SOFORTUW_SECRET' => 'Password',
    'SHOP_MODULE_GC_SOFORTUW_PURPOSE' => 'Verwendungszweck',
    'HELP_SHOP_MODULE_GC_SOFORTUW_PURPOSE' => 'Sie können in Ihrem Verwendungszweck die folgenden Platzhalter verwenden:
                                                                    <ul>
                                                                      <li style="list-style: inherit;">{ORDERID}: Bestellnummer</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERID}: Kundennummer</li>
                                                                      <li style="list-style: inherit;">{SHOPNAME}: Shop Name</li> 
                                                                      <li style="list-style: inherit;">{CUSTOMERNAME}: Kundenname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERFIRSTNAME}: Kunde Vorname</li>
                                                                      <li style="list-style: inherit;">{CUSTOMERLASTNAME}: Kunde Nachname</li>
                                                                    </ul>
                                                                    Beispiel: Wenn Ihr Verwendungszweck "Best. 55342, TestShop" lauten soll, dann müssen Sie folgendes angeben: "Best. {ORDERID}, {SHOPNAME}".
                                                                    Die maximale Länge beträgt 27 Zeichen.',
);
