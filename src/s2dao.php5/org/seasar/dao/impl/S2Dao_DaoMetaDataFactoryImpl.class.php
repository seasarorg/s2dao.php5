<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
class S2Dao_DaoMetaDataFactoryImpl implements S2Dao_DaoMetaDataFactory {

    protected $daoMetaDataCache;
    protected $dataSource;
    protected $statementFactory;
    protected $resultSetFactory;
    protected $readerFactory;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_ResultSetFactory $resultSetFactory = null,
                                S2Dao_AnnotationReaderFactory $readerFactory) {

        $this->daoMetaDataCache = new S2Dao_HashMap();
        $this->dataSource = $dataSource;
        $this->statementFactory = $this->createStatementFactory($statementFactory);
        $this->resultSetFactory = $this->createResultSetFactory($resultSetFactory);
        $this->readerFactory = $readerFactory;
    }
    
    public function createStatementFactory(S2Dao_StatementFactory $statementFactory = null){
        if($statementFactory === null){
            return new S2Dao_BasicStatementFactory();
        }
        return $statementFactory;
    }
    
    public function createResultSetFactory(S2Dao_ResultSetFactory $resultSetFactory = null){
        if($resultSetFactory === null){
            return new S2Dao_BasicResultSetFactory();
        }
        return $resultSetFactory;
    }

    public function getDaoMetaData(ReflectionClass $daoClass) {
        $key = $daoClass->getName();
        $dmd = $this->daoMetaDataCache->get($key);
        if ($dmd !== null) {
            return $dmd;
        }
        $dmd = new S2Dao_DaoMetaDataImpl($daoClass,
                                         $this->dataSource,
                                         $this->statementFactory,
                                         $this->resultSetFactory,
                                         $this->readerFactory);
        $this->daoMetaDataCache->put($key, $dmd);
        return $dmd;
    }
}
?>
