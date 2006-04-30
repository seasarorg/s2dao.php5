<?php

/**
 * @author nowel
 */
class S2DaoAssertAtLeastOneRowInterceptor extends S2Container_AbstractInterceptor {

    public function invoke(S2Container_MethodInvocation $invocation){
        $result = $invocation->proceed();
        if (is_number($result)) {
            $rows = (int)$result;
            if ($rows < 1) {
                throw new S2Dao_NoRowsUpdatedRuntimeException();
            }
        }
        return $result;
    }
}
?>