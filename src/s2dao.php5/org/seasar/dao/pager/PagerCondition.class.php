<?php

/**
 * ページャ条件オブジェクトのインターフェイス
 */
interface PagerCondition {
    
    const NONE_LIMIT = -1;
    
    /**
     * 検索結果の総件数を取得します。
     * @return 総件数
     */
    public function getCount();
    
    /**
     * 検索結果の総件数をセットします。
     * @param count 総件数
     */
    public function setCount( $count );
    
    /**
     * 検索結果から一度に取得する最大件数を取得します。
     * @return 最大件数
     */
    public function getLimit();
    
    /**
     * 検索結果から一度に取得する最大件数をセットします。
     * @param limit 最大件数
     */
    public function setLimit( $limit );
    
    /**
     * 検索結果の取得開始位置ををセットします。
     * @param offset 取得開始位置
     */
    public function setOffset( $offset );

    /**
     * 検索結果の取得開始位置をを取得します。
     * @return 取得開始位置
     */
    public function getOffset();
}

?>