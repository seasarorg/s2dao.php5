<?php

/**
 * @auther yonekawa
 */
class PagerResultSetWrapper
{
    /**
     * S2Daoの結果をDTOの条件でラップして返します
     * @param result S2Daoの結果
     * @param condition DTO
     */
    public static function create($result, $condition)
    {
        $retValue = new S2Dao_ArrayList();
        if(!($result instanceof S2Dao_ArrayList)){
            $result = new S2Dao_ArrayList(new ArrayObject($result));
            $returnArray = true;
        }
        
        $condition->setCount($result->size());
     
        $limit = $condition->getOffset() + $condition->getLimit();
        $count = $condition->getCount();
        $start = $condition->getOffset() == null ? 0 : $condition->getOffset();
        for($i = $start; $i < $limit && $i < $count; $i++){
            $retValue->add($result->get($i));
        }

        if($returnArray){
            return $retValue->toArray();
        }
        return $retValue;
    }

}

?>
