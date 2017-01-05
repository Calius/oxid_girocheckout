[{if $oView->getActiveCurrencyName() == "EUR"}]
    [{if $oView->isSettingsSet($sPaymentID) == true}]
        <dl>
          <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
                <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
          </dt>
          <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
                <img src="[{$oViewConf->getModuleUrl('girocheckout','images/')}]Logo_iDeal_50_px.jpg" border="0" alt="Logo iDEAL">

                [{block name="checkout_payment_longdesc"}]
                    <div class="desc">
                        [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                            [{ $paymentmethod->oxpayments__oxlongdesc->value nofilter}]
                        [{else}]
                            [{ oxmultilang ident="GC_IDEAL_DESCRIPTION" }]
                        [{/if}]
                    </div>
                [{/block}]
                [{ assign var="dynvalue" value=$oView->getDynValue()}]
                [{ assign var="issuers" value=$oView->getIssuers()}]
                <ul class="form">
                    <li>
                        <label>[{ oxmultilang ident="GC_ISSUERS" }]:</label>
                        <select id="gc_ideal_issuer" name="dynvalue[gc_ideal_issuer]" size="1" style="width:auto">
                            [{foreach from=$issuers item=value}]
                                <option value="[{$value.value}]" [{if $dynvalue.gc_ideal_issuer == $value.value}] SELECTED [{/if}] > [{$value.text}] </option>
                            [{/foreach}]
                        </select>
                    </li>
                </ul>
            </dd>
        </dl>
    [{/if}]  
[{/if}]    
