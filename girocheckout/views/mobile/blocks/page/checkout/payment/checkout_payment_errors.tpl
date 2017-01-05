[{if $oView->isGirocheckoutPaymentError() === TRUE}]
<div class="status error invalid-field">[{ $oView->getGirocheckoutPaymentError() }]</div>
[{else}]
[{$smarty.block.parent}]
[{/if}]