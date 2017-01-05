[{if $oView->getActiveCurrencyName() == "EUR"}]
    [{if $oView->isSettingsSet($sPaymentID) == true}]
        <dl>
            <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
                <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
            </dt>
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
                [{capture assign=gcDirectdebitJavascript}]

                    $(document).ready(function() {
                        $("input[name='dynvalue[gc_directdebit_cdatabankcheck]']").click(function() {

                            var rbValor = $(this).val();
                            if(rbValor === "rbIbanDirectdebit"){
                                $("#divBankDatasDirectdebit").hide();
                                $("#divIbanDirectdebit").show();

                                $( "#girocheckout_directdebit_bankaccount" ).val("").removeClass( "js-oxValidate js-oxValidate_notEmpty" );
                                $( "#girocheckout_directdebit_bankcode" ).val("").removeClass( "js-oxValidate js-oxValidate_notEmpty" );      
                                $( "#girocheckout_directdebit_iban" ).addClass( "js-oxValidate js-oxValidate_notEmpty" );

                            }else{
                                $("#divIbanDirectdebit").hide();
                                $("#divBankDatasDirectdebit").show();


                                $( "#girocheckout_directdebit_bankaccount" ).addClass( "js-oxValidate js-oxValidate_notEmpty" );
                                $( "#girocheckout_directdebit_bankcode" ).addClass( "js-oxValidate js-oxValidate_notEmpty" );
                                $( "#girocheckout_directdebit_iban" ).val("").removeClass( "js-oxValidate js-oxValidate_notEmpty" );

                            }
                        });
                    });
                [{/capture}]
                [{oxscript add=$gcDirectdebitJavascript}]
                
                [{ assign var="gcShowDataBank" value=$oView->showGiroCheckoutDataBankConfig()}]

                <img src="[{$oViewConf->getModuleUrl('girocheckout','images/')}]Logo_EC_50_px.jpg" border="0" alt="Logo Direct Debit">

                [{block name="checkout_payment_longdesc"}]
                    <div class="desc">
                        [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                            [{ $paymentmethod->oxpayments__oxlongdesc->value nofilter}]
                        [{else}]
                            [{ oxmultilang ident="GC_DIRECTDEBIT_DESCRIPTION" }]
                        [{/if}]
                    </div>
                [{/block}]
                [{ assign var="dynvalue" value=$oView->getDynValue()}]
                
                [{if $gcShowDataBank}]
                    <p>
                        <input type="radio" name="dynvalue[gc_directdebit_cdatabankcheck]" id="rbIbanDirectdebit" [{if $dynvalue.gc_directdebit_cdatabankcheck == 'rbIbanDirectdebit'}]checked[{elseif empty($dynvalue.gc_directdebit_cdatabankcheck)}]checked[{/if}] value="rbIbanDirectdebit"/><label for="rbIbanDirectdebit">[{ oxmultilang ident="GC_IBAN" }]</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="dynvalue[gc_directdebit_cdatabankcheck]" id="rbBankDatasDirectdebit" [{if $dynvalue.gc_directdebit_cdatabankcheck == 'rbBankDatasDirectdebit'}]checked[{/if}] value="rbBankDatasDirectdebit"/><label for="rbBankDatasDirectdebit">[{ oxmultilang ident="GC_BLZ" }]</label>
                    </p>
                [{/if}]
                <ul class="form">
                    <li>
                        <label>[{ oxmultilang ident="GC_ACCOUNTHOLDER" }]:</label>
                        <input type="text" size="20" maxlength="27" class="js-oxValidate js-oxValidate_notEmpty" name="dynvalue[gc_directdebit_accountholder]" value="[{$dynvalue.gc_directdebit_accountholder}]">
                    </li>
                    <div id ="divIbanDirectdebit" style="display: [{if $dynvalue.gc_directdebit_cdatabankcheck == 'rbIbanDirectdebit'}]block[{elseif empty($dynvalue.gc_directdebit_cdatabankcheck)}]block[{elseif !$gcShowDataBank}]block[{else}]none[{/if}];">
                        <li>
                            <label>[{ oxmultilang ident="GC_IBAN" }]:</label>
                            <input type="text" size="20" maxlength="22" class="js-oxValidate js-oxValidate_notEmpty" name="dynvalue[gc_directdebit_iban]" value="[{$dynvalue.gc_directdebit_iban}]">
                        </li>
                    </div>

                    [{if $gcShowDataBank}]    
                        <div id ="divBankDatasDirectdebit" style="display: [{if $dynvalue.gc_directdebit_cdatabankcheck == 'rbBankDatasDirectdebit'}]block[{else}]none[{/if}];">
                            <li>
                                <label>[{ oxmultilang ident="GC_ACCOUNT" }]:</label>
                                <input type="text" size="20" maxlength="10" class="js-oxValidate js-oxValidate_notEmpty" name="dynvalue[gc_directdebit_account]" value="[{$dynvalue.gc_directdebit_account}]">
                            </li>
                            <li>
                                <label>[{ oxmultilang ident="GC_BANKCODE" }]:</label>
                                <input type="text" size="20" maxlength="8" class="js-oxValidate js-oxValidate_notEmpty" name="dynvalue[gc_directdebit_bankcode]" value="[{$dynvalue.gc_directdebit_bankcode}]">
                            </li>
                        </div>
                    [{/if}]
                </ul>
            </dd>
        </dl>
    [{/if}]     
[{/if}] 