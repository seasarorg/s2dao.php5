<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author nowel
 */
final class S2Dao_TxRule {
    
    private $tx = null;
    private $exceptionClass;
    private $commit;

    public function __construct(S2Dao_AbstractTxInterceptor $tx,
                                Exception $exceptionClass, $commit) {
        $this->tx = $tx;
        $this->exceptionClass = $exceptionClass;
        $this->commit = $commit;
    }

    public function isAssignableFrom($t) {
        return $t instanceof Exception;
    }

    public function complete() {
        if ($this->commit) {
            $this->tx->commit();
        } else {
            $this->tx->rollback();
        }
        return $this->commit;
    }
}
?>