[{ assign var="dynvalue" value=$oView->getDynValue()}]
<div id="paymentOption_[{$sPaymentID}]" class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
    <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
        
    [{oxstyle include=https://bankauswahl.giropay.de/widget/v2/style.css priority=1}]

    <script src ="https://bankauswahl.giropay.de/widget/v2/girocheckoutwidget.js" type="text/javascript"></script>

    <ul class="form">
        <li>
            <img src="[{$oViewConf->getModuleUrl('girocheckout','images/')}]Logo_eps_50_px.jpg" border="0" alt="Logo eps">
        </li>
        <li>
            [{block name="checkout_payment_longdesc"}]
                <div class="desc">
                    [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                        [{ $paymentmethod->oxpayments__oxlongdesc->value nofilter}]
                    [{else}]
                        [{ oxmultilang ident="GC_EPS_DESCRIPTION" }]
                    [{/if}]
                </div>
            [{/block}]
        </li>
        <li>
            <label>[{ oxmultilang ident="GC_BIC" }]:</label> 
            <input name="dynvalue[gc_eps_bic]" class="js-oxValidate js-oxValidate_notEmpty" id="eps_widget" onkeyup="girocheckout_widget(this, event, 'bic', '3')" maxlength="11" type="text" size="40" value="[{ $dynvalue.gc_eps_bic }]" autocomplete="off" style="z-index:1000;"/>[{ oxmultilang ident="GC_BIC_TEXTINPUTINFO" }]
        </li>
    </ul>
</div>
  