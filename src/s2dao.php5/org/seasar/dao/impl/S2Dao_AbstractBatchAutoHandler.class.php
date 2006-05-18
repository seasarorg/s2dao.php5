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
abstract class S2Dao_AbstractBatchAutoHandler extends S2Dao_AbstractAutoHandler {

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    public function execute($args, $arg2 = null) {
        $connection = $this->getConnection();
        $ps = $this->prepareStatement($connection);
        $ps->setFetchMode(PDO::FETCH_ASSOC);
        
        if ($args[0] instanceof S2Dao_ArrayList) {
            $beans = $args[0]->toArray();
        } else {
            $beans = (array)$args;
        }
        if ($beans == null) {
            throw new S2Container_IllegalArgumentException('args[0]');
        }

        $ret = -1;
        for ($i = 0; $i < count($beans); ++$i) {
            $this->preUpdateBean($beans[$i]);
            $this->setupBindVariables($beans[$i]);

            if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
                $this->getLogger()->debug(
                    $this->getCompleteSql($this->getBindVariables())
                );
            }

            $this->bindArgs($ps, $this->getBindVariables(),
                            $this->getBindVariableTypes());

            $this->postUpdateBean($beans[$i]);
            $result = $ps->execute();
            
            if(false === $result){
                $this->getLogger()->error($result->getMessage(), __METHOD__);
                $this->getLogger()->error($result->getDebugInfo(), __METHOD__);
                $connection->disconnect();
                throw new Exception();
            } else {
                $ret += $ps->rowCount();
            }
        }

        unset($ps);
        unset($connection);
        return $ret;
    }
}
?>
