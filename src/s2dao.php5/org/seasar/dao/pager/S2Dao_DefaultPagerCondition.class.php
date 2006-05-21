<?php

/**
 * ページャ条件保持オブジェクトのベースクラス。
 * @author yonekawa
 */
class S2Dao_DefaultPagerCondition implements S2Dao_PagerCondition
{
    /** 現在の位置 */
    private $offset = 0;

    /** 表示の最大値 */
    private $limit = self::NONE_LIMIT;

    /** 取得した総数 */
    private $count = 0;
    
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
    public function setCount($total) 
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
    public function setLimit($limit) 
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
    public function setOffset($offset) 
    {
        $this->offset = $offset;
    }
    
}

?>
