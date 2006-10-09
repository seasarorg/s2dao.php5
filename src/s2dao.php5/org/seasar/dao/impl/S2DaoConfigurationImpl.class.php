<?php

/**
 * @author nowel
 */
class S2DaoConfigurationImpl implements S2DaoConfiguration {

    /**
     * INSERT文を自動生成するメソッド名につけるprefix
     */
    protected $insertPrefixes = array('insert', 'create', 'add');
    
    public function setInsertPrefixes(array $prefixes) {
        $this->insertPrefixes = $prefixes;
    }
    
    public function isInsertMethod(ReflectionMethod $method) {
        $c = count($this->insertPrefixes);
        for ($i = 0; $i < $c; ++$i) {
            $insertPrefix = $this->insertPrefixes[$i];
            if (stripos($method->getName(), $insertPrefix) === 0) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * DELETE文を自動生成するメソッド名につけるprefix
     */
    protected $deletePrefixes = array('delete', 'remove');
    
    public function setDeletePrefixes(array $prefixes) {
        $this->deletePrefixes = $prefixes;
    }

    public function isDeleteMethod(ReflectionMethod $method) {
        $c = count($this->deletePrefixes);
        for ($i = 0; $i < $c; ++$i) {
            $deletePrefix = $this->deletePrefixes[$i];
            if (stripos($method->getName(), $deletePrefix) === 0) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * UPDATE文を自動生成するメソッド名につけるprefix
     */
    protected $updatePrefixes = array('update', 'modify', 'store');

    public function setUpdatePrefixes(array $prefixes) {
        $this->updatePrefixes = $prefixes;
    }

    public function isUpdateMethod(ReflectionMethod $method) {
        $c = count($this->updatePrefixes);
        for ($i = 0; $i < $c; ++$i) {
            $updatePrefix = $this->updatePrefixes[$i];
            if (stripos($method->getName(), $updatePrefix) === 0) {
                return true;
            }
        }
        return false;
    }
    
    public function isSelectMethod(ReflectionMethod $method) {
        if ($this->isDeleteMethod($method) ||
            $this->isInsertMethod($method) ||
            $this->isUpdateMethod($method)) {
            return false;
        }
        return true;
    }
    
    private $daoSuffixes;
    
    public function daoSuffixes(array $daoSuffixes) {
        $this->daoSuffixes = $daoSuffixes;
    }

    public function getDaoSuffixes() {
        return $this->daoSuffixes;
    }
}

?>