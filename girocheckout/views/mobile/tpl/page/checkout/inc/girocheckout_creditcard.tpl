<div id="paymentOption_[{$sPaymentID}]" class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
    <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
        
    <ul class="form">
        <li>
            <img src="[{$oViewConf->getModuleUrl('girocheckout','images/')}][{$oView->getGiroCheckoutCreditCardLogo()}]" border="0" alt="Logo Credit Card">
        </li>
        <li>
            [{block name="checkout_payment_longdesc"}]
                <div class="desc">
                    [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                        [{ $paymentmethod->oxpayments__oxlongdesc->value nofilter}]
                    [{else}]
                        [{ oxmultilang ident="GC_CREDITCARD_DESCRIPTION" }]
                    [{/if}]
                </div>
            [{/block}]
        </li>
    </ul>
</div>