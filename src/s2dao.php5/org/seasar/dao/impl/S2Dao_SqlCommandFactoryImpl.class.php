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
class S2Dao_SqlCommandFactoryImpl implements S2Dao_SqlCommandFactory {
    
    const startWithOrderByPattern = '/(\/\*[^*]+\*\/)*order by/i';
    const startWithSelectPattern = '/^\s*select\s/i';
    const NOT_SINGLE_ROW_UPDATED = 'NotSingleRowUpdated';

    protected $annotationReaderFactory;
    protected $dataSource;
    protected $statementFactory;
    protected $resultSetFactory;
    protected $configuration;
    protected $autoSelectSqlCreator;

    public function __construct(S2Dao_AutoSelectSqlCreator $autoSelectSqlCreator,
                                S2Dao_DaoNamingConvention $configuration,
                                S2Dao_AnnotationReaderFactory $annotationReaderFactory,
                                S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory,
                                S2Dao_ResultSetFactory $resultSetFactory) {
        $this->autoSelectSqlCreator = $autoSelectSqlCreator;
        $this->annotationReaderFactory = $annotationReaderFactory;
        $this->configuration = $configuration;
        $this->dataSource = $dataSource;
        $this->statementFactory = $statementFactory;
        $this->resultSetFactory = $resultSetFactory;
    }

    /**
     * @return SqlCommand
     */
    public function createSqlCommand(S2Dao_Dbms $dbms,
                                     S2Dao_DaoAnnotationReader $annotationReader,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method,
                                     S2Dao_SqlWrapper $sql) {
        if ($sql instanceof S2Dao_ProcedureSqlWrapper) {
            $procedureSqlWrapper = $sql;
            $returnType = $annotationReader->getReturnType($method);
            $resultSetHandler = $this->createResultSetHandler($dbms,
                                                              $beanMetaData,
                                                              $returnType);
            $procedureHandler = new S2Dao_ProcedureHandlerImpl($procedureSqlWrapper,
                                                               $this->dataSource,
                                                               $this->statementFactory,
                                                               $this->resultSetHandler,
                                                               $returnType);
            return new S2Dao_StaticStoredProcedureCommand($procedureHandler);
        } else if ($this->configuration->isSelectMethod($method)) {
            return $this->setupSelectMethodByManual($dbms,
                                                    $annotationReader,
                                                    $beanMetaData,
                                                    $method, $sql);
        } else {
            return $this->setupUpdateMethodByManual($annotationReader,
                                                    $beanMetaData,
                                                    $method, $sql);
        }
    }
    
    /**
     * @return SelectDynamicCommand
     */
    public function createSelectDynamicCommandByQuery(S2Dao_Dbms $dbms,
                                                      S2Dao_BeanMetaData $beanMetaData,
                                                      S2Dao_ReturnType $returnType,
                                                      array $joinData = null,
                                                      $query) {
        $sql = $this->autoSelectSqlCreator->createSelectSql($dbms, $beanMetaData, $joinData, $query);
        $resultSetHandler = $this->createResultSetHandler($dbms, $beanMetaData, $returnType);
        $selectCommand = new S2Dao_SelectDynamicCommand($this->dataSource,
                                                        $this->statementFactory,
                                                        $resultSetHandler,
                                                        $this->resultSetFactory);
        $selectCommand->setSql($sql);
        return $selectCommand;
    }
    
    /**
     * @author SelectDynamicCommand
     */
    public function createSelectDynamicCommand(S2Dao_ResultSetHandler $rsh, $sql) {
        $selectCommand = new S2Dao_SelectDynamicCommand($this->dataSource,
                                                        $this->statementFactory,
                                                        $rsh,
                                                        $this->resultSetFactory);
        $selectCommand->setSql($sql);
        return $selectCommand;
    }

    protected function isUpdateSignatureForBean(S2Dao_BeanMetaData $beanMetaData,
                                                ReflectionMethod $method) {
        $params = $method->getParameters();
        $param0 = $params[0];
        return count($params) == 1 && $beanMetaData->isBeanClassAssignable($param0->getClass());
    }

    /**
     * @return SqlCommand
     */
    protected function setupSelectMethodByManual(S2Dao_Dbms $dbms,
                                                 S2Dao_DaoAnnotationReader $annotationReader,
                                                 S2Dao_BeanMetaData $beanMetaData,
                                                 ReflectionMethod $method,
                                                 S2Dao_SqlWrapper $sql) {
        $returnType = $annotationReader->getReturnType($method);
        $rsh = $this->createResultSetHandler($dbms, $beanMetaData, $returnType);
        $cmd = $this->createSelectDynamicCommand($rsh, $sql->getSql());
        
        $cmd->setArgNames($sql->getParameterNames());
        $cmd->setArgTypes($method->getParameters());
        return $cmd;
    }

    /**
     * @return SqlCommand
     */
    protected function setupUpdateMethodByManual(S2Dao_DaoAnnotationReader $annotationReader,
                                                 S2Dao_BeanMetaData $beanMetaData,
                                                 ReflectionMethod $method,
                                                 S2Dao_SqlWrapper $sql) {
        $argNames = $sql->getParameterNames();
        if (count($argNames) == 0 &&
            $this->isUpdateSignatureForBean($beanMetaData, $method)) {
            $argNames = array(ucwords($beanMetaData->getBeanClass()->getName()));
        }
        $handler = null;
        // TODO: Batch:: $sql->isBatch()
        if(false){
            $handler = new S2Dao_BasicBatchUpdateHandler($this->dataSource, $sql,
                                                        $method->getParameters(),
                                                        $this->statementFactory);
        } else {
            $handler = new S2Dao_BasicUpdateHandlerEx($this->dataSource, $sql,
                                                    $argNames,
                                                    $method->getParameters(),
                                                    $this->statementFactory);
        }
        return new S2Dao_UpdateDynamicCommand($this->dataSource,
                                              $this->statementFactory,
                                              $handler);
    }

    /**
     * @return ResultSetHandler
     */
    public function createResultSetHandler(S2Dao_Dbms $dbms,
                                           S2Dao_BeanMetaData $beanMetaData, 
                                           S2Dao_ReturnType $returnType) {
        return $returnType->createResultSetHandler($beanMetaData, $dbms,
                    $this->createRelationPropertyHandler($beanMetaData, $dbms));
    }
    
    /**
     * @return RelationPropertyHandler[]
     */
    protected function createRelationPropertyHandler(S2Dao_BeanMetaData $beanMetaData,
                                                     S2Dao_Dbms $dbms) {
        $list = new S2Dao_ArrayList();
        $c = $beanMetaData->getOneToManyRelationPropertyTypeSize();
        for ($i = 0; $i < $c; ++$i) {
            $rpt = $beanMetaData->getManyRelationPropertyType($i);
            if ($rpt === null) {
                continue;
            }
            $clazz = $rpt->getPropertyDesc()->getPropertyType();
            $command = null;
            if ($clazz->isArray()) {
                $bmd = $rpt->getBeanMetaData();
                if($rpt->getJoinTableName() == null){
                    $command = $this->createSelectDynamicCommandByQuery(
                                        $dbms, $bmd, $clazz, null,
                                        $this->createWhere($rpt));
                } else {
                    $joinData = new S2Dao_JoinData(S2Dao_JoinType::INNER_JOIN,
                                                   $rpt->getJoinTableName(),
                                                   $rpt->getYourKeys(),
                                                   $rpt->getYourJoinKeys());
                    $command = $this->createSelectDynamicCommandByQuery(
                                        $dbms, $bmd, $clazz, array($joinData),
                                        $this->createJoinWhere($rpt));
                }
                $list->add(new S2Dao_ArrayRelationPropertyHandler($beanMetaData,
                                                                  $rpt,
                                                                  $command));
            } else if ($clazz instanceof S2Dao_List) {
                $bmd = $rpt->getBeanMetaData();
                if($rpt->getJoinTableName() == null){
                    $command = $this->createSelectDynamicCommandByQuery(
                                        $dbms, $bmd, $clazz, null,
                                        $this->createWhere($rpt));
                } else {
                    $joinData = new S2Dao_JoinData(S2Dao_JoinType::INNER_JOIN,
                                                   $rpt->getJoinTableName(),
                                                   $rpt->getYourKeys(),
                                                   $rpt->getYourJoinKeys());
                    $command = $this->createSelectDynamicCommandByQuery(
                                        $dbms, $bmd, $clazz, array($joinData),
                                        $this->createJoinWhere($rpt));
                }
                $list->add(new S2Dao_ListRelationPropertyHandler($beanMetaData,
                                                                 $rpt,
                                                                 $command));
            }
        }
        return $list->toArray();

    }

    protected function createWhere(S2Dao_RelationPropertyType $relationPropertyType) {
        $buf = '';
        $c = $relationPropertyType->getKeySize();
        for ($i = 0; $i < $c; ++$i) {
            if (0 < $i) {
                $buf .= ' AND ';
            }
            $yourKey = $relationPropertyType->getYourKey($i);
            $buf .= $yourKey;
            $buf .= ' = ?';
        }
        return $buf;
    }

    protected function createJoinWhere(S2Dao_RelationPropertyType $relationPropertyType) {
        $buf = '';
        $c = $relationPropertyType->getKeySize();
        for ($i = 0; $i < $c; ++$i) {
            if (0 < $i) {
                $buf .= ' AND ';
            }
            $buf .= $relationPropertyType->getJoinTableName();
            $buf .= '.';
            $buf .= $relationPropertyType->getMyJoinKey($i);
            $buf .= ' = ?';
        }
        return $buf;
    }
    
}

?>