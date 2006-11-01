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
class S2Dao_DaoMetaDataFactoryImpl implements S2Dao_DaoMetaDataFactory {
    
    protected $autoSelectCommandCreator;

    protected $daoMetaDataCache = null;

    protected $sqlWrapperCreators = array();

    protected $dataSource;

    protected $readerFactory;

    protected $sqlCommandFactory;

    protected $configuration;

    protected $beanMetaDataCache = null;

    public function __construct(
            S2Dao_SqlCommandFactory $sqlCommandFactory,
            S2Container_DataSource $dataSource,
            S2Dao_AnnotationReaderFactory $readerFactory,
            S2Dao_AutoSelectSqlCreator $autoSelectCommandCreator,
            S2Dao_DaoNamingConvention $configuration) {
        $this->sqlCommandFactory = $sqlCommandFactory;
        $this->dataSource = $dataSource;
        $this->readerFactory = $readerFactory;
        $this->autoSelectCommandCreator = $autoSelectCommandCreator;
        $this->configuration = $configuration;
        $this->daoMetaDataCache = new S2Dao_HashMap();
        $this->beanMetaDataCache = new S2Dao_HashMap();
    }

    public function setSqlWrapperCreators(array $sqlWrapperCreators = null) {
        // TODO: DIContainer definition
        $this->sqlWrapperCreators = array(
            new S2Dao_AnnotationSqlWrapperCreator($this->readerFactory),
            new S2Dao_AutoSelectSqlWrapperCreatorImpl($this->readerFactory),
            new S2Dao_DeleteAnnotationSqlWrapperCreator($this->readerFactory, $this->configuration),
            new S2Dao_DeleteAutoSqlWrapperCreator($this->readerFactory, $this->configuration),
            new S2Dao_FileSqlWrapperCreator($this->readerFactory),
            new S2Dao_UpdateSqlWrapperCreator($this->readerFactory, $this->configuration),
        );
    }
    
    /**
     * @return SqlCommandFactory
     */
    public function getSqlCommandFactory() {
        return $this->sqlCommandFactory;
    }
    
    /**
     * @return DaoMetaData
     */
    public function getDaoMetaData(ReflectionClass $daoClass) {
        $key = $daoClass->getName();
        $dmd = $this->daoMetaDataCache->get($key);
        if ($dmd !== null) {
            return $dmd;
        }
        $dmdi = new S2Dao_DaoMetaDataImpl($daoClass,
                                          $this->dataSource,
                                          $this->readerFactory,
                                          $this->configuration->getDaoSuffixes());
        $this->setupSqlCommand($dmdi);
        $this->daoMetaDataCache->put($key, $dmdi);
        return $dmdi;
    }

    protected function setupSqlCommand(S2Dao_DaoMetaDataImpl $daoMetaDataImpl) {
        $idbd = $daoMetaDataImpl->getDaoBeanDesc();
        $names = $idbd->getMethodNames();
        $c = count($names);
        for ($i = 0; $i < $c; ++$i) {
            $methods = $idbd->getMethods($names[$i]);
            if (S2Container_MethodUtil::isAbstract($methods)) {
                $this->setupMethod($daoMetaDataImpl, $methods);
            }
        }
    }
    
    protected function setupMethod(S2Dao_DaoMetaDataImpl $daoMetaDataImpl,
                                   ReflectionMethod $method) {
        $daoAnnotationReader = $daoMetaDataImpl->getDaoAnnotationReader();
        $beanMetaData = $this->getBeanMetaData($daoAnnotationReader->getBeanClass($method));
        $dbms = $daoMetaDataImpl->getDbms();
        $sqlWrapperCreators =& $this->sqlWrapperCreators;
        $sqlCommandFactory =& $this->sqlCommandFactory;
        
        $length = $this->sqlWrapperCreators;
        for ($i = 0; $i < $length; $i++) {
            $sqlWrapper = $sqlWrapperCreators[$i]->createSqlCommand($dbms,
                                                                    $daoMetaDataImpl, 
                                                                    $beanMetaData,
                                                                    $method);
            if ($sqlWrapper !== null) {
                $command = $sqlCommandFactory->createSqlCommand($dbms,
                                                                $daoAnnotationReader,
                                                                $beanMetaData,
                                                                $method,
                                                                $sqlWrapper);
                $daoMetaDataImpl->setSqlCommand($method->getName(), $command);
                return;
            }
        }
    }
    
    /**
     * @return BeanMetaData
     */
    public function getBeanMetaData(ReflectionClass $beanClass){
        $beanClassName = $beanClass->getName();
        $beanMetaData = $this->beanMetaDataCache->get($beanClassName);
        if($beanMetaData === null){
            $conn = $this->dataSource->getConnection();
            $beanMetaDataImpl = new S2Dao_BeanMetaDataImpl();
            $beanMetaDataImpl->setBeanClass($beanClass);
            $beanMetaDataImpl->setDatabaseMetaData($conn);
            $beanMetaDataImpl->setAnnotationReaderFactory($this->readerFactory);
            $beanMetaDataImpl->initialize();
            $beanMetaData = $beanMetaDataImpl;
            $this->beanMetaDataCache->put($beanClassName, $beanMetaData);
        }
        return $beanMetaData;
    }
    
    /**
     * @return BeanMetaData
     */
    public function getBeanMetaDataByDaoClass(ReflectionClass $daoClass){
        $daoMetaData = $this->getDaoMetaData($daoClass);
        $daoAnnotationReader = $daoMetaData->getDaoAnnotationReader();
        return $this->getBeanMetaData($daoAnnotationReader->getBeanClass(null));
    }

}
?>
