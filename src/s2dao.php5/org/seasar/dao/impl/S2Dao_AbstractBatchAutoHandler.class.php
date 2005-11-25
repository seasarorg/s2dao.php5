<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractBatchAutoHandler extends S2Dao_AbstractAutoHandler {

    public function __construct(S2Container_DataSource $dataSource,
                                //S2Dao_StatementFactory $statementFactory,
                                $statementFactory,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    public function execute($args, $bean = null) {
        if( is_array($args) ){
            $connection = $this->getConnection();
            $connection->setFetchMode(DB_FETCHMODE_ASSOC);
        
            $beans = array();
            if ($args[0] instanceof S2Dao_ArrayList) {
                $beans = $args[0]->toArray();
            } else {
                $beans = (array)$args;
            }
            if ($beans == null) {
                throw new S2Container_IllegalArgumentException("args[0]");
            }

            $ps = $this->prepareStatement($connection);
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
                $result = $connection->execute($ps, $this->getBindVariables());
            }
            
            if(DB::isError($result)){
                $this->getLogger()->error($result->getMessage(), __METHOD__);
                $this->getLogger()->error($result->getDebugInfo(), __METHOD__);
                $connection->disconnect();
                throw new Exception();
            }

            if($result == DB_OK){
                $ret = $connection->affectedRows();
            }

            $connection->disconnect();
            return $ret;
        } else {
            throw new Exception( get_class($args) );
        }
    }
}
?>