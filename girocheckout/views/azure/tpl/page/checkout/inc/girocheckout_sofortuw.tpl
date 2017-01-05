[{if $oView->getActiveCurrencyName() == "EUR"}]
    [{if $oView->isSettingsSet($sPaymentID) == true}]
        <dl>
            <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
                <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
            </dt>
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
                <img src="[{$oViewConf->getModuleUrl('girocheckout','images/')}]Logo_sofort_50_px.jpg" border="0" alt="Logo SOFORT Überweisung">

                [{block name="checkout_payment_longdesc"}]
                    <div class="desc">
                        [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                            [{ $paymentmethod->oxpayments__oxlongdesc->value nofilter}]
                        [{else}]
                            [{ oxmultilang ident="GC_SOFORTUW_DESCRIPTION" }]
                        [{/if}]
                    </div>
                [{/block}]

            </dd>
        </dl>
    [{/if}]
[{/if}]