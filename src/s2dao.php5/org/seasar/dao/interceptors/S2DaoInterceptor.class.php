<?php

/**
 * @author nowel
 */
class S2DaoInterceptor extends S2Container_AbstractInterceptor {

    private $daoMetaDataFactory_;

    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory) {
        $this->daoMetaDataFactory_ = $daoMetaDataFactory;
    }

    public function invoke(S2Container_MethodInvocation $invocation){
        try{
            $method = $invocation->getMethod();
            if (!S2Container_MethodUtil::isAbstract($method)) {
                return $invocation->proceed();
            }
            
            $targetClass = $this->getTargetClass($invocation);
            $dmd = $this->daoMetaDataFactory_->getDaoMetaData($targetClass);
            $cmd = $dmd->getSqlCommand($method->getName());
            $ret = $cmd->execute($invocation->getArguments());
            
            /*
            $retType = $method->returnsReference();
            if ( is_string($retType) ||
                 is_integer($retType) ||
                 is_double($retType) ) {
                return S2Dao_NumberConversionUtil::convertPrimitiveWrapper($retType, $ret);
            } else if( is_numeric($retType) ){
                return S2Dao_NumberConversionUtil::convertNumber($retType, $ret);
            }
            */

            return $ret;
        } catch (Exception $e){
            throw $e;
        }
    }
}
?>
