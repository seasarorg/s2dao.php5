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
class S2Dao_DaoMetaDataImpl implements S2Dao_DaoMetaData {

    protected $daoClass;

    protected $daoInterface;

    protected $daoBeanDesc;

    protected $dataSource;

    protected $daoAnnotationReader;

    protected $annotationReaderFactory;

    protected $dbms;

    protected $sqlCommands = null;

    protected $daoSuffixes = array('Dao');
    
    private $valueTypeFactory;

    public function __construct(ReflectionClass $daoClass,
                                S2Container_DataSource $dataSource,
                                S2Dao_AnnotationReaderFactory $annotationReaderFactory = null,
                                array $daoSuffixes = null,
                                S2Dao_ValueTypeFactory $valueTypeFactory = null) {
        $this->setDaoClass($daoClass);
        $this->setDataSource($dataSource);
        if($annotationReaderFactory === null){
            $annotationReaderFactory = new FieldAnnotationReaderFactory();
        }
        $this->setAnnotationReaderFactory($annotationReaderFactory);
        if ($daoSuffixes !== null) {
            $this->setDaoSuffixes($daoSuffixes);
        }
        if($valueTypeFactory !== null){
            $this->setValueTypeFactory($valueTypeFactory);
        }
        
        $this->sqlCommands = new S2Dao_HashMap();
        $this->initialize();
    }

    public function initialize() {
        $annoFactory = $this->getAnnotationReaderFactory();
        $daoClass = $this->getDaoClass();
        $this->daoInterface = $this->getDaoInterface($daoClass);
        $daoBeanDesc = S2Container_BeanDescFactory::getBeanDesc($daoClass);
        $this->daoBeanDesc = $daoBeanDesc;
        $this->daoAnnotationReader = $annoFactory->createDaoAnnotationReader($daoBeanDesc);
        $conn = $this->dataSource->getConnection();
        $this->dbms = S2DaoDbmsManager::getDbms($conn);
    }

    /**
     * @return Dbms
     */
    public function getDbms() {
        return $this->dbms;
    }
    
    public function setDbms(S2Dao_Dbms $dbms) {
        $this->dbms = $dbms;
    }

    protected function completedSetupMethod(ReflectionMethod $method) {
        return $sqlCommands->get($method->getName()) !== null;
    }

    /**
     * @return BeanDesc
     */
    public function getDaoBeanDesc() {
        return $this->daoBeanDesc;
    }
    
    /**
     * @return DaoAnnotationReader
     */
    public function getDaoAnnotationReader() {
        return $this->daoAnnotationReader;
    }
    
    public function setSqlCommand($methodName, S2Dao_SqlCommand $cmd) {
        $this->sqlCommands->put($methodName, $cmd);
    }

    /**
     * @see org.seasar.dao.DaoMetaData#getSqlCommand(string)
     * @return SqlCommand
     */
    public function getSqlCommand($methodName) {
        $cmd = $this->sqlCommands->get($methodName);
        if ($cmd === null) {
            throw new S2Container_MethodNotFoundRuntimeException($this->daoClass,
                                                                 $methodName,
                                                                 null);
        }
        return $cmd;
    }

    /**
     * @see org.seasar.dao.DaoMetaData#hasSqlCommand(java.lang.String)
     */
    public function hasSqlCommand($methodName) {
        return $this->sqlCommands->containsKey($methodName);
    }

    /**
     * @return ReflectionClass
     */
    public function getDaoInterface(ReflectionClass $clazz) {
        if ($clazz->isInterface()) {
            return $clazz;
        }
        $abstractDao = 'S2Dao_AbstractDao';
        for ($target = $clazz; $target->getName != $abstractDao;
                               $target = $target->getParentClass()) {
            $interfaces = $target->getInterfaces();
            foreach($interfaces as $intf){
                $c = count($this->daoSuffixes);
                for ($j = 0; $j < $c; $j++) {
                    if(0 == strcasecmp($intf->getName(), $this->daoSuffixes[$j])){
                        return $intf;
                    }
                }
            }
        }
        throw new S2Dao_DaoNotFoundRuntimeException($clazz);
    }

    /**
     * @return AnnotationReaderFactory
     */
    protected function getAnnotationReaderFactory() {
        return $this->annotationReaderFactory;
    }

    public function setAnnotationReaderFactory(
                    S2Dao_AnnotationReaderFactory $annotationReaderFactory) {
        $this->annotationReaderFactory = $annotationReaderFactory;
    }

    public function setDaoSuffixes(array $daoSuffixes) {
        $this->daoSuffixes = $daoSuffixes;
    }

    /**
     * @param $dataSource S2Container_DataSource
     */
    public function setDataSource(S2Container_DataSource $dataSource) {
        $this->dataSource = $dataSource;
    }

    /**
     * @return ReflectionClass
     */
    protected function getDaoClass() {
        return $this->daoClass;
    }

    public function setDaoClass(ReflectionClass $daoClass) {
        $this->daoClass = $daoClass;
    }
    
    /**
     * @return ValueTypeFactory
     */
    protected function getValueTypeFactory() {
        return $this->valueTypeFactory;
    }

    /**
     * @param $valueTypeFactory S2Dao_ValueTypeFactory
     */
    public function setValueTypeFactory(S2Dao_ValueTypeFactory $valueTypeFactory) {
        $this->valueTypeFactory = $valueTypeFactory;
    }

}

?>
