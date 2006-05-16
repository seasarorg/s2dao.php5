<?php

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
