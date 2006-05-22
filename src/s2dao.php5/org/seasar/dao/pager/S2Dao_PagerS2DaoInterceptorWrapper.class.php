<?php

/**
 * S2DaoInterceptor�㉃b�v����C���^�[�Z�v�^
 * @author yonekawa
 */
class S2Dao_PagerS2DaoInterceptorWrapper extends S2Container_AbstractInterceptor
{

    /** �I���W�i����S2DaoInteceptor */
    private $interceptor_;

    /** @param interceptor �I���W�i����S2DaoInterceptor */
    public function __construct(S2DaoInterceptor $interceptor) 
    {
        $this->interceptor_ = $interceptor;
    }

    /**
     * �C���^�[�Z�v�g�������\�b�h�̈�PagerCondition�̎��N���X���B���
     * S2DaoInterceptor�̌��ʂ�PagerCondition�Ń��b�v�������ʂ�Ԃ��܂�
     */
    public function invoke(S2Container_MethodInvocation $invocation) 
    {
        $args = $invocation->getArguments();
        $result = $this->interceptor_->invoke($invocation);

        if (count($args) == 1) {
            return $result;
        }

        if (is_array($result) || $result instanceof S2Dao_ArrayList) {
            if ($args[0] instanceof S2Dao_PagerCondition) {
                $dto = $args[0];
                return S2Dao_PagerResultSetWrapper::create($result, $dto);
            }
        }
        
        return $result;
    }
    
}

?>
