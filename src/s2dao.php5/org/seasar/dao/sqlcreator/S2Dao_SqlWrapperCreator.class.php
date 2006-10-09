<?php

/**
 * @author nowel
 */
interface S2Dao_SqlWrapperCreator {
    public function createSqlCommand(S2Dao_Dbms $dbms,
                                    S2Dao_DaoMetaData $daoMetaData,
                                    S2Dao_BeanMetaData $beanMetaData,
                                    ReflectionMethod $method);
}

?>