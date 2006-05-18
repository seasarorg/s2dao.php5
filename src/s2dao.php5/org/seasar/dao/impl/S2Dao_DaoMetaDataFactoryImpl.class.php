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
class S2Dao_DaoMetaDataFactoryImpl implements S2Dao_DaoMetaDataFactory {

    protected $daoMetaDataCache_ = null;
    protected $dataSource_ = null;
    protected $statementFactory_ = null;
    protected $resultSetFactory_ = null;
    protected $readerFactory_ = null;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_ResultSetFactory $resultSetFactory = null,
                                S2Dao_AnnotationReaderFactory $readerFactory) {

        $this->daoMetaDataCache_ = new S2Dao_HashMap();
        $this->dataSource_ = $dataSource;
        $this->statementFactory_ = $statementFactory;
        $this->resultSetFactory_ = $resultSetFactory;
        $this->readerFactory_ = $readerFactory;
    }

    public function getDaoMetaData(ReflectionClass $daoClass) {
        $key = $daoClass->getName();
        $dmd = $this->daoMetaDataCache_->get($key);
        if ($dmd !== null) {
            return $dmd;
        }
        $dmd = new S2Dao_DaoMetaDataImpl($daoClass,
                                         $this->dataSource_,
                                         $this->statementFactory_,
                                         $this->resultSetFactory_,
                                         $this->readerFactory_);
        $this->daoMetaDataCache_->put($key, $dmd);
        return $dmd;
    }
}
?>
