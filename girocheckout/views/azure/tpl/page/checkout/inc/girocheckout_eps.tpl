[{if $oView->getActiveCurrencyName() == "EUR"}]
    [{if $oView->isSettingsSet($sPaymentID) == true}]

        [{oxstyle include=https://bankauswahl.giropay.de/widget/v2/style.css priority=1}]

        <script src ="https://bankauswahl.giropay.de/widget/v2/girocheckoutwidget.js" type="text/javascript"></script>

        <dl>
            <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
                <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
            </dt>
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">

                <img src="[{$oViewConf->getModuleUrl('girocheckout','images/')}]Logo_eps_50_px.jpg" border="0" alt="Logo eps">

                [{block name="checkout_payment_longdesc"}]
                    <div class="desc">
                        [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                            [{ $paymentmethod->oxpayments__oxlongdesc->value nofilter}]
                        [{else}]
                            [{ oxmultilang ident="GC_EPS_DESCRIPTION" }]
                        [{/if}]
                    </div>
                [{/block}]

                [{ assign var="dynvalue" value=$oView->getDynValue()}]

                <ul class="form">
                    <li>
                        <label>[{ oxmultilang ident="GC_BIC" }]:</label>
                        <input name="dynvalue[gc_eps_bic]" class="js-oxValidate js-oxValidate_notEmpty" id="eps_widget" onkeyup="girocheckout_widget(this, event, 'bic', '3')" maxlength="11" type="text" size="40" value="[{ $dynvalue.gc_eps_bic }]" autocomplete="off" style="z-index:1000;"/>[{ oxmultilang ident="GC_BIC_TEXTINPUTINFO" }]
                    </li>
                </ul>
            </dd>
        </dl>
    [{/if}]   
[{/if}] 