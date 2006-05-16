<?php

/**
 * @auther yonekawa
 */
class PagerS2DaoInterceptorWrapper extends S2Container_AbstractInterceptor
{

    /** オリジナルのS2DaoInteceptor */
    private $interceptor_;

    /** @param interceptor オリジナルのS2DaoInterceptor */
    public function __construct( S2DaoInterceptor $interceptor ) 
    {
        $this->interceptor_ = $interceptor;
    }

    /**
     * インターセプトしたメソッドの引数がPagerConditionの実装クラスだったら
     * S2DaoInterceptorの結果をPagerConditionでラップした結果を返します
     */
    public function invoke( S2Container_MethodInvocation $invocation ) 
    {
        $args = $invocation->getArguments();

        $result = $this->interceptor_->invoke( $invocation );

        if ( !sizeof( $args ) ) {
            return $result;
        }

        if ( is_array( $result ) ||
             $result instanceof S2Dao_ArrayList ) {
            
            if ( $args[0] instanceof PagerCondition ) {
                return 
                    PagerResultSetWrapper::create( $result,
                                                   $args[0] );
            }
        }
        
        return $result;
    }
    
}

?>
