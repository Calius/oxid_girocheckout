<?php

/**
 * Class girocheckout_oxbasket
 */
class girocheckout__oxbasket extends girocheckout__oxbasket_parent
{
    /**
     * Returns array of basket oxarticle objects
     *
     * @return array
     */
    public function getBasketArticles()
    {
        $aBasketArticles = array();
        foreach ( $this->_aBasketContents as $sItemKey => $oBasketItem ) {
            /** @var oxBasketItem $oBasketItem */
            try {
                /* Add check for session-parameter, disabled artickel-stock check START */
                if(oxRegistry::getSession()->hasVariable('girocheckout_disable_article_check')
                && oxRegistry::getSession()->getVariable('girocheckout_disable_article_check') =='1')
                {
                    $oProduct = $oBasketItem->getArticle( false );
                }
                else
                {
                    $oProduct = $oBasketItem->getArticle( true );
                }
                /* Add check for session-parameter, disabled artickel-stock check END */

                if ( $this->getConfig()->getConfigParam( 'bl_perfLoadSelectLists' ) ) {
                    // marking chosen select list
                    $aSelList = $oBasketItem->getSelList();
                    if ( is_array( $aSelList ) && ( $aSelectlist = $oProduct->getSelectLists( $sItemKey ) ) ) {
                        reset( $aSelList );
                        while ( list( $conkey, $iSel ) = each( $aSelList ) ) {
                            $aSelectlist[$conkey][$iSel]->selected = 1;
                        }
                        $oProduct->setSelectlist( $aSelectlist );
                    }
                }
            } catch ( oxNoArticleException $oEx ) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay( $oEx );
                $this->removeItem( $sItemKey );
                $this->calculateBasket( true );
                continue;
            } catch ( oxArticleInputException $oEx ) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay( $oEx );
                $this->removeItem( $sItemKey );
                $this->calculateBasket( true );
                continue;
            }
            $aBasketArticles[$sItemKey] = $oProduct;
        }
        return $aBasketArticles;
    }
}
