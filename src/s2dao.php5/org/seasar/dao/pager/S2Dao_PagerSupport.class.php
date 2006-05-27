<?php

/**
 * DTOのセッションへの格納をサポートします。
 * @author yonekawa
 */
class S2Dao_PagerSupport
{
    /** 最大取得件数  */
    private $limit;

    /** 検索条件クラス名  */
    private $pagerConditionClass;
    
    /** 検索条件オブジェクトのセッション中の名前 */
    private $pagerConditionName;

    /**
     * コンストラクタ
     * セッションを開始して、指定された最大取得件数,検索条件クラス名,
     * 検索条件オブジェクトのセッション中の名前で新しいPagerSupportインスタンスを生成します
     * @param limit 最大取得件数
     * @param pagerConditionClass 検索条件クラス名
     * @param pagerConditionName 検索条件オブジェクトのセッション中の名前
     */
    public function __construct($limit, $pagerConditionClass, $pagerConditionName)
    {
        if (!headers_sent()) {
            session_start();
        }
        $this->limit = $limit;
        $this->pagerConditionClass = $pagerConditionClass;
        $this->pagerConditionName = $pagerConditionName;
    }

    /** 
     * Session中の検索条件オブジェクトのoffsetを更新します
     * @param offset 更新Offset
     */
    public function updateOffset($offset)
    {
        $pagerCondition = $this->getPagerCondition();
        if (empty($offset)) {
            $offset = 0;
        }   
        $pagerCondition->setOffset($offset);
    }
    
    /**
     * セッション中の検索条件オブジェクトを取得します。
     * 検索条件オブジェクトが存在しない場合、新規に検索条件オブジェクトを生成します。
     * @return 検索条件オブジェクト
     */
    public function getPagerCondition()
    {        
        $dto = $_SESSION[$this->pagerConditionName];
        
        if (empty($dto)) {
            $refClass = new ReflectionClass($this->pagerConditionClass);
            $dto = $refClass->newInstance();
            $dto->setLimit($this->limit);

            $_SESSION[$this->pagerConditionName] = $dto;
        }
        
        return $dto;
    }

}

?>