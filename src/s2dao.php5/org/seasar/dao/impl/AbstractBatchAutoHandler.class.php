<?php

/**
 * @author nowel
 */
abstract class AbstractBatchAutoHandler extends AbstractAutoHandler {

    public function __construct(DataSource $dataSource,
                                  //StatementFactory $statementFactory,
                                  $statementFactory,
                                  BeanMetaData $beanMetaData,
                                  $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    public function execute($args, $bean = null) {
        if( is_array($args) ){
            $connection = $this->getConnection();
            $connection->setFetchMode(DB_FETCHMODE_ASSOC);
        
            $beans = array();
            if ($args[0] instanceof ArrayList) {
                $beans = $args[0]->toArray();
            } else {
                $beans = (array)$args;
            }
            if ($beans == null) {
                throw new IllegalArgumentException("args[0]");
            }

            $ps = $this->prepareStatement($connection);
            for ($i = 0; $i < count($beans); ++$i) {
                $this->preUpdateBean($beans[$i]);
                $this->setupBindVariables($beans[$i]);

//                if($this->getLogger()->isDebugEnabled()){
//                    $this->getLogger()->debug(
//                        $this->getCompleteSql($this->getBindVariables())
//                    );
//                }

                $this->bindArgs($ps, $this->getBindVariables(),
                                $this->getBindVariableTypes());
                $this->postUpdateBean($beans[$i]);
                $res = $connection->execute($ps, $this->getBindVariables());
                var_dump($this->getCompleteSql($this->getBindVariables()));
            }
            
            if(DB::isError($res)){
                //$this->log_->error($result->getMessage(), __METHOD__);
                //$this->log_->error($result->getDebugInfo(), __METHOD__);
                var_dump($res->getMessage());
                var_dump($res->getDebuginfo());
                $connection->disconnect();
                exit;
            }

            if($res == DB_OK){
                return $connection->affectedRows();
            }

            /*
            $ret = array();
            while($row = $res->fetchRow()){
                array_push($ret, $resultSetHandler->handle($row));
            }
            */
            
            $res->free();
            $db->disconnect();
            return $ret;
        } else {
            throw new Exception( get_class($args) );
        }
    }
}
?>
