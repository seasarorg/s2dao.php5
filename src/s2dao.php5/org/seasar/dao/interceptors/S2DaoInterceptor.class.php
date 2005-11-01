<?php

/**
 * @author nowel
 */
class S2DaoInterceptor extends AbstractInterceptor {

    private $daoMetaDataFactory_;

    public function __construct(DaoMetaDataFactory $daoMetaDataFactory) {
        $this->daoMetaDataFactory_ = $daoMetaDataFactory;
    }

    public function invoke(MethodInvocation $invocation){
        try{
            $method = $invocation->getMethod();
            if (!MethodUtil::isAbstract($method)) {
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
                return NumberConversionUtil::convertPrimitiveWrapper($retType, $ret);
            } else if( is_numeric($retType) ){
                return NumberConversionUtil::convertNumber($retType, $ret);
            }
            */

            return $ret;
        } catch ( Exception $e ){
            throw $e;
        }
    }
}
?>
