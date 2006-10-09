<?php

/**
 * @author nowel
 */
abstract class S2Dao_AutoSqlWrapperCreator implements S2Dao_SqlWrapperCreator {
    
    protected $annotationReaderFactory;
    protected $configuration;

    public function __construct(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory,
            S2DaoConfiguration $configuration) {
        $this->annotationReaderFactory = $annotationReaderFactory;
        $this->configuration = $configuration;
    }

    protected function createUpdateWhere(S2Dao_BeanMetaData $beanMetaData) {
        $buf = '';
        $buf .= ' WHERE ';
        $c = $beanMetaData->getPrimaryKeySize();
        for ($i = 0; $i < $c; ++$i) {
            if (0 < $i) {
                $buf .= ' AND ';
            }
            $pkName = $beanMetaData->getPrimaryKey($i);
            $buf .= $pkName;
            $buf .= ' = /*dto.';
            $buf .= $beanMetaData->getPropertyType($pkName)->getPropertyName();
            $buf .= '*/';
        }
        if ($beanMetaData->hasVersionNoPropertyType()) {
            $pt = $beanMetaData->getVersionNoPropertyType();
            $buf .= ' AND ';
            $buf .= $pt->getColumnName();
            $buf .= ' = /*dto.';
            $buf .= $pt->getPropertyName();
            $buf .= '*/';
        }
        if ($beanMetaData->hasTimestampPropertyType()) {
            $pt = $beanMetaData->getTimestampPropertyType();
            $buf .= ' AND ';
            $buf .= $pt->getColumnName();
            $buf .= ' = /*dto.';
            $buf .= $pt->getPropertyName();
            $buf .= '*/';
        }
        return $buf;
    }

    protected function isUpdateSignatureForBean(S2Dao_BeanMetaData $beanMetaData,
                                                ReflectionMethod $method) {
        $parameters = $method->getParameters();
        return count($parameters) == 1 &&
               $beanMetaData->isBeanClassAssignable($parameters[0]);
    }

    protected function checkAutoUpdateMethod(S2Dao_BeanMetaData $beanMetaData,
                                             ReflectionMethod $method) {
        $parameters = $method->getParameters();
        $param0 = $parameters[0];
        return count($parameters) == 1
                && 
                ($beanMetaData->isBeanClassAssignable($param0)
                || $param0 instanceof S2Dao_ArrayList
                || $param0->isArray());
    }

    protected function isPropertyExist(array $props, $propertyName) {
        $c = count($props);
        for ($i = 0; $i < $c; ++$i) {
            if (strcasecmp($props[$i], $propertyName) == 0) {
                return true;
            }
        }
        return false;
    }

    protected function getPersistentPropertyNames(
                S2Dao_DaoAnnotationReader $daoAnnotationReader,
                S2Dao_BeanMetaData $beanMetaData,
                ReflectionMethod $method) {
        $names = new ArrayList();
        $props = $daoAnnotationReader->getNoPersistentProps($method);
        if ($props != null) {
            $c = $beanMetaData->getPropertyTypeSize();
            for ($i = 0; $i < $c; ++$i) {
                $pt = $beanMetaData->getPropertyType($i);
                if ($pt->isPersistent()
                        && !$this->isPropertyExist($props, $pt->getPropertyName())) {
                    $names->add($pt->getPropertyName());
                }
            }
        } else {
            $props = $daoAnnotationReader->getPersistentProps($method);
            if ($props != null) {
                $names->addAll($props);
                $c = $beanMetaData->getPrimaryKeySize();
                for ($i = 0; $i < $c; ++$i) {
                    $pk = $beanMetaData->getPrimaryKey($i);
                    $pt = $beanMetaData->getPropertyTypeByColumnName($pk);
                    $names->add($pt->getPropertyName());
                }
                if ($beanMetaData->hasVersionNoPropertyType()) {
                    $names->add($beanMetaData->getVersionNoPropertyName());
                }
                if ($beanMetaData->hasTimestampPropertyType()) {
                    $names->add($beanMetaData->getTimestampPropertyName());
                }
            }
        }
        if ($names->size() == 0) {
            $size = $beanMetaData->getPropertyTypeSize();
            for ($i = 0; $i < $size; ++$i) {
                $pt = $beanMetaData->getPropertyType($i);
                if ($pt->isPersistent()) {
                    $names->add($pt->getPropertyName());
                }
            }
        }
        return $names->toArray();
    }

}

?>