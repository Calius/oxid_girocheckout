[{ assign var="dynvalue" value=$oView->getDynValue()}]
[{ assign var="issuers" value=$oView->getIssuers()}]
<div id="paymentOption_[{$sPaymentID}]" class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
    <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
    <ul class="form">
        <li>
            <img src="[{$oViewConf->getModuleUrl('girocheckout','images/')}]Logo_iDeal_50_px.jpg" border="0" alt="Logo iDEAL">  
        </li>
        <li>
            [{block name="checkout_payment_longdesc"}]
                <div class="desc">
                    [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                        [{ $paymentmethod->oxpayments__oxlongdesc->value nofilter}]
                    [{else}]
                        [{ oxmultilang ident="GC_IDEAL_DESCRIPTION" }]
                    [{/if}]
                </div>
            [{/block}]
        </li>
        <li>
            <label>[{ oxmultilang ident="GC_ISSUERS" }]:</label>
            <select id="gc_ideal_issuer" name="dynvalue[gc_ideal_issuer]" size="1" style="width:auto">
		[{foreach from=$issuers item=value}]
                    <option value="[{$value.value}]" [{if $dynvalue.gc_ideal_issuer == $value.value}] SELECTED [{/if}] > [{$value.text}] </option>
		[{/foreach}]
            </select>
        </li>
    </ul>
</div>