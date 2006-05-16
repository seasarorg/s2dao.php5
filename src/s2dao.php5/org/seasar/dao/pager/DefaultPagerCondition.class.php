<?php

require_once dirname( __FILE__ ) . "/PagerCondition.class.php";

/**
 * ページャ条件保持オブジェクトのベースクラス。
 * 
 * @author Toshitaka Agata(Nulab,inc.)
 */
class DefaultPagerCondition implements PagerCondition
{
    /** 現在の位置 */
    private $offset;

    /** 表示の最大値 */
    private $limit = self::NONE_LIMIT;

    /** 取得した総数 */
    private $count;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
    }
    
    /**
     * @return Returns the total.
     */
    public function getCount() 
    {
        return $this->count;
    }

    /**
     * @param total The total to set.
     */
    public function setCount( $total ) 
    {
        $this->count = $total;
    }

    /**
     * @return Returns the limit.
     */
    public function getLimit() 
    {
        return $this->limit;
    }

    /**
     * @param limit The limit to set.
     */
    public function setLimit( $limit ) 
    {
        $this->limit = $limit;
    }

    /**
     * @return Returns the offset.
     */
    public function getOffset() 
    {
        return $this->offset;
    }
    
    /**
     * @param offset The offset to set.
     */
    public function setOffset( $offset ) 
    {
        $this->offset = $offset;
    }
    
}

?>
