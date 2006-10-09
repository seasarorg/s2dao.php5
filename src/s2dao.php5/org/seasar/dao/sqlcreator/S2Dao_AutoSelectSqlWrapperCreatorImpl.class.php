<?php

/**
 * @author nowel
 */
class S2Dao_AutoSelectSqlWrapperCreatorImpl
implements S2Dao_SqlWrapperCreator, S2Dao_AutoSelectSqlCreator {

    const startWithOrderByPattern = "/(\/\*[^*]+\*\/)*order by/i";
    const startWithSelectPattern = "/^\s*select\s/i";
    const startWithBeginCommentPattern = "/\/\*BEGIN\*\/\s*WHERE .+/i";
    
    private $valueTypeFactory;
    private $annotationReaderFactory;
    
    public function __construct(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory,
            S2Dao_ValueTypeFactory $valueTypeFactory) {
        $this->annotationReaderFactory = $annotationReaderFactory;
        $this->valueTypeFactory = $valueTypeFactory;
    }

    protected static function startsWithOrderBy($query = null) {
        if ($query == null) {
            return false;
        }
        return preg_match(self::startWithOrderByPattern, trim($query));
    }

    protected static function startsWithSelect($query = null) {
        if ($query == null) {
            return false;
        }
        return preg_match(self::startWithSelectPattern, trim($query));
    }
    
    protected static function startsWithBeginComment($query = null) {
        if($query == null){
            return false;
        }
        return preg_match(self::startWithBeginCommentPattern, trim($query));
    }
    
    public function createSelectSql(S2Dao_Dbms $dbms,
                                    S2Dao_BeanMetaData $beanMetaData,
                                    array $joinData = null,
                                    $query) {
        if(null === $joinData){
            $joinData = array();
            $joinData[] = new S2Dao_JoinData();
        }
        
        $buf = '';
        if (self::startsWithSelect($query)) {
            $buf .= $query;
        } else {
            $sql = $dbms->getAutoSelectSql($beanMetaData, $joinData);
            $buf .= $sql;
            if ($query != null) {
                if (self::startsWithOrderBy($query)) {
                    $buf .= ' ';
                } else if (self::startsWithBeginComment($query)) {
                    $buf .= ' ';
                } else if (stripos($sql, 'WHERE') === false) {
                    $buf .= ' WHERE ';
                } else {
                    $buf .= ' AND ';
                }
                $buf .= $query;
            }
        }
        return $buf;
    }

    protected function createAutoSelectSql(S2Dao_Dbms $dbms,
                                           S2Dao_BeanMetaData $beanMetaData,
                                           array $argNames) {
        $sql = $dbms->getAutoSelectSql($beanMetaData);
        $buf = $sql;
        if (count($argNames) != 0) {
            $began = false;
            if (stripos($sql, 'WHERE') === false) {
                $buf .= '/*BEGIN*/ WHERE ';
                $began = true;
            }
            $c = count($argNames);
            for ($i = 0; $i < $c; ++$i) {
                $argName = $argNames[$i];
                $columnName = $beanMetaData->convertFullColumnName($argName);
                $buf .= '/*IF ';
                $buf .= $argName;
                $buf .= ' != null*/';
                $buf .= ' ';
                if (!$began || $i != 0) {
                    $buf .= 'AND ';
                }
                $buf .= $columnName;
                $buf .= ' = /*';
                $buf .= $argName;
                $buf .= '*/null';
                $buf .= '/*END*/';
            }
            if ($began) {
                $buf .= '/*END*/';
            }
        }
        return $buf;
    }
    
    protected function createAutoSelectSqlByDto(S2Dao_Dbms $dbms,
                                                S2Dao_BeanMetaData $beanMetaData,
                                                $dtoClass) {
        $sql = $dbms->getAutoSelectSql($beanMetaData);
        $buf = $sql;
        
        if (!($dtoClass instanceof ReflectionClass)) {
            return $sql;
        }
        $dmd = new S2Dao_DtoMetaDataImpl($dtoClass,
                $this->annotationReaderFactory->createBeanAnnotationReader($dtoClass),
                $this->valueTypeFactory);
        $began = false;
        if (stripos($sql, 'WHERE') === false) {
            $buf .= '/*BEGIN*/ WHERE ';
            $began = true;
        }
        $c = $dmd->getPropertyTypeSize();
        for ($i = 0; $i < $c; ++$i) {
            $pt = $dmd->getPropertyType($i);
            $aliasName = $pt->getColumnName();
            if (!$beanMetaData->hasPropertyTypeByAliasName($aliasName)) {
                continue;
            }
            if (!$beanMetaData->getPropertyTypeByAliasName($aliasName)->isPersistent()) {
                continue;
            }
            $columnName = $beanMetaData->convertFullColumnName($aliasName);
            $propertyName = 'dto.' . $pt->getPropertyName();
            $buf .= '/*IF ';
            $buf .= $propertyName;
            $buf .= ' != null*/';
            $buf .= ' ';
            if (!$began || $i != 0) {
                $buf .= 'AND ';
            }
            $buf .= $columnName;
            $buf .= ' = /*';
            $buf .= $propertyName;
            $buf .= '*/null';
            $buf .= '/*END*/';
        }
        if ($began) {
            $buf .= '/*END*/';
        }
        return $buf;
    }

    public function createSqlCommand(S2Dao_Dbms $dbms,
                                     S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        $beanDesc = $daoMetaData->getDaoBeanDesc();
        $reader = $this->annotationReaderFactory->createDaoAnnotationReader($beanDesc);
        $query = $reader->getQuery($method);
        $sql = '';
        $argNames = $reader->getArgNames($method);
        if ($query != null && !self::startsWithOrderBy($query)) {
            $sql = $this->createSelectSql($dbms, $beanMetaData, $query);
        } else {
            $parameters = $method->getParameters();
            if (count($argNames) == 0 && count($parameters) == 1) {
                $argNames = array('dto');
                $sql = $this->createAutoSelectSqlByDto($dbms,
                                                       $beanMetaData,
                                                       $parameters[0]);
            } else {
                $sql = $this->createAutoSelectSql($dbms, $beanMetaData, $argNames);
            }
            if ($query != null) {
                $sql .= ' ' . $query;
            }
        }
        return new S2Dao_SqlWrapperImpl($reader->getArgNames($method), $sql);
    }

}

?>