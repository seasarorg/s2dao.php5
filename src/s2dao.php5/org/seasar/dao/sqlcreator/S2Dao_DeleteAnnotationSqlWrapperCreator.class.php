<?php

/**
 * @author nowel
 */
class S2Dao_DeleteAnnotationSqlWrapperCreator extends S2Dao_AutoSqlWrapperCreator {

    public function __construct(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory,
            S2DaoConfiguration $configuration) {
        super($annotationReaderFactory, $configuration);
    }

    protected function createSql(S2Dao_BeanMetaData $beanMetaData, $query) {
        $beanMetaData->checkPrimaryKey();
        $buf = '';
        $buf .= 'DELETE FROM ';
        $buf .= $beanMetaData->getTableName();
        if(0 < strlen(trim($query))){
            $buf .= ' WHERE ';
            $buf .= $query;
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
        $query = $reader->getQuery($method);
        if ($query === null) {
            return null;
        }
        return new S2Dao_SqlWrapperImpl($reader->getArgNames($method),
                                        $this->createSql($beanMetaData, $query));
    }
}

?>