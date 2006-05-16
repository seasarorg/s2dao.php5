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
    public static function create( $result, $condition )
    {
        $resultSet = new S2Dao_ArrayList();
        
        $condition->setCount( sizeof( $result ) );
     
        for ( $i = $condition->getOffset(); 
              ($i < ( $condition->getOffset() + $condition->getLimit() ) ) 
                  && ( $i < $condition->getCount() ); 
              $i++ ) {

            $resultSet->add( $result[ $i ] );
        
        }

        if ( !( $result instanceof S2Dao_ArrayList ) ) {
            $resultSet = $resultSet->toArray();
        }
        
        return $resultSet;    
    }

}

?>