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
// $Id: $
//
/**
 * @author nowel
 */
class S2Dao_DeleteAutoSqlWrapperCreator extends S2Dao_AutoSqlWrapperCreator {

    public function __construct(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory,
            S2DaoConfiguration $configuration) {
        super($annotationReaderFactory, $configuration);
    }

    protected function createSql(S2Dao_BeanMetaData $beanMetaData) {
        $beanMetaData->checkPrimaryKey();
        $buf = '';
        $buf .= 'DELETE FROM ';
        $buf .= $beanMetaData->getTableName();
        $buf .= $this->createUpdateWhere($beanMetaData);
        return $buf;
    }
    
    protected function createAutoDelete(S2Dao_Dbms $dbms,
                                        S2Dao_BeanMetaData $beanMetaData,
                                        array $argNames) {
        $buf = '';
        $buf .= 'DELETE FROM ';
        $buf .= $beanMetaData->getTableName();
        $buf .= ' WHERE ';
        $began = true;
        $c = count($argNames);
        if ($c != 0) {
            for ($i = 0; $i < $c; ++$i) {
                $argName = $argNames[$i];
                $columnName = $beanMetaData->convertFullColumnName($argName);
                $buf .= '/*IF ';
                $buf .= $argName;
                $buf .= ' != null*/';
                $buf .= ' ';
                if (!$began) {
                    $buf .= 'AND ';
                }
                $buf .= $columnName;
                $buf .= ' = /*';
                $buf .= $argName;
                $buf .= '*/null';
                $buf .= '/*END*/';
            }
            $began = false;
        }
        return $buf;
    }

    /**
     * @return SqlWrapper
     */
    public function createSqlCommand(S2Dao_Dbms $dbms,
                                     S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        if (!$this->configuration->isDeleteMethod($method)) {
            return null;
        }
        $beanDesc = $daoMetaData->getDaoBeanDesc();
        $reader = $this->annotationReaderFactory->createDaoAnnotationReader($beanDesc);
        if($this->checkAutoUpdateMethod($beanMetaData, $method)){
            if ($this->isUpdateSignatureForBean($beanMetaData, $method)) {
                return new S2Dao_SqlWrapperImplAnony(array('dto'), $this->createSql($beanMetaData), false);
            }
            return new S2Dao_SqlWrapperImpl(array('dto'), $this->createSql($beanMetaData), true);
        }
        return new S2Dao_SqlWrapperImpl($reader->getArgNames($method),
                                        $this->createAutoDelete($dbms, $beanMetaData,
                                                                $reader->getArgNames($method)), false);
    }
}

final class S2Dao_SqlWrapperImplAnony extends S2Dao_SqlWrapperImpl {
    
    public function preUpdateBean(S2Dao_CommandContext $ctx) {
    }

    public function postUpdateBean(S2Dao_CommandContext $ctx,
                                   $returnValue) {
        $rows = $returnValue;
        if ((int)$rows != 1) {
            throw new S2Dao_NotSingleRowUpdatedRuntimeException(
                       $ctx->getArg('dto'), (int)$rows);
        }
    }
}


?>