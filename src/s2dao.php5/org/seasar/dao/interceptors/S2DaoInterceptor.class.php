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

            return $ret;
        } catch (Exception $e){
            throw $e;
        }
    }
}
?>
