<?php

/**
 * @author nowel
 */
class S2Dao_FileSqlWrapperCreator implements S2Dao_SqlWrapperCreator {

    protected $encoding = 'auto';
    private $annotationReaderFactory;

    public function __construct(S2Dao_AnnotationReaderFactory $annotationReaderFactory) {
        $this->annotationReaderFactory = $annotationReaderFactory;
    }

    protected function readText($path) {
        //return mb_convert_encoding(file_get_contents($path), $this->encoding);
        return file_get_contents($path);
    }

    /**
     * @return SqlWrapper
     */
    public function createSqlCommand(S2Dao_Dbms $dbms,
                                     S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        $daoBeanDesc = $daoMetaData->getDaoBeanDesc();
        $reader = $this->annotationReaderFactory->createDaoAnnotationReader($daoBeanDesc);
        $daoClass = $daoBeanDesc->getBeanClass();
        $command = $this->createSqlCommand2($dbms, $reader, $daoClass, $beanMetaData, $method);
        if ($command !== null) {
            return $command;
        }
        $interfaces = $daoClass->getInterfaces();
        $c = count($interfaces);
        for ($i = 0; $i < $c; $i++) {
            $interface = $interfaces[$i];
            $interfaceMethod = $this->getSameSignatureMethod($interface, $method);
            if ($interfaceMethod !== null) {
                $command = $this->createSqlCommand2($dbms, $reader, $interface,
                                    $beanMetaData, $interfaceMethod);
                if ($command !== null) {
                    return $command;
                }
            }
        }
        return null;
    }

    /**
     * @return SqlWrapper
     */
    protected function createSqlCommand2(S2Dao_Dbms $dbms,
                                         S2Dao_DaoAnnotationReader $reader,
                                         ReflectionClass $clazz,
                                         S2Dao_BeanMetaData $beanMetaData,
                                         ReflectionMethod $method) {
        $dir = dirname($clazz->getFileName()) . DIRECTORY_SEPARATOR;
        $base = $dir . $clazz->getName() . '_' . $method->getName();
        $dbmsPath = $base . $dbms->getSuffix() . '.sql';
        $standardPath = $base . '.sql';
        if (file_exists($dbmsPath)) {
            $sql = $this->readText($dbmsPath);
            return new S2Dao_SqlWrapperImpl($reader->getArgNames($method), $sql);
        } else if (file_exists($standardPath)) {
            $sql = $this->readText($standardPath);
            return new S2Dao_SqlWrapperImpl($reader->getArgNames($method), $sql);
        }
        return null;
    }

    private function getSameSignatureMethod(ReflectionClass $clazz, ReflectionMethod $method) {
        try {
            return S2Container_ClassUtil::getMethod($clazz, $method->getName());
        } catch (S2Container_NoSuchMethodRuntimeException $e) {
            return null;
        }
    }

}

?>