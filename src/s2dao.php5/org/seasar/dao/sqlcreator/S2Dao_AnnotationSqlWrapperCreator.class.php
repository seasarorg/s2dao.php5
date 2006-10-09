<?php

/**
 * @author nowel
 */
class S2Dao_AnnotationSqlWrapperCreator implements S2Dao_SqlWrapperCreator {

    private $annotationReaderFactory;

    /**
     * 
     */
    public function __construct(S2Dao_AnnotationReaderFactory $annotationReaderFactory) {
        $this->annotationReaderFactory = $annotationReaderFactory;
    }

    /**
     * @return SqlWrapper
     */
    public function createSqlCommand(S2Dao_Dbms $dbms, S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        $beanDesc = $daoMetaData->getDaoBeanDesc();
        $reader = $this->annotationReaderFactory->createDaoAnnotationReader($beanDesc);
        $sql = $reader->getSQL($method, $dbms->getSuffix());
        if ($sql !== null) {
            return new S2Dao_SqlWrapperImpl($reader->getArgNames($method), $sql);
        }
        return null;
    }

}

?>