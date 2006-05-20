<?php

/**
 * S2DaoInterceptor(Limit句をつける)
 * @author yonekawa
 */
class S2Dao_LimitOffsetS2DaoInterceptor extends S2DaoInterceptor {

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

            $args = $invocation->getArguments();
            print_r($args);
            if (sizeof($args)) {
                print_r($args);
                if ($args[0] instanceof S2Dao_PagerCondition) {
                    $cmd = S2Dao_SelectDynamicCommandLimitOffsetWrapperFactory::create($cmd);
                }
            }

            $ret = $cmd->execute($invocation->getArguments());

            return $ret;
        } catch (Exception $e){
            throw $e;
        }
    }
}
?>