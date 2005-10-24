<?php

/**
 * @author nowel
 */
interface EntityManager {

    /**
    public List find(String query);
    public List find(String query, Object arg1);
    public List find(String query, Object arg1, Object arg2);
    public List find(String query, Object arg1, Object arg2, Object arg3);
    */
    public function find($query, $arg1 = null, $arg2 = null, $arg3 = null);
    
    /**
    public Object[] findArray(String query);
    public Object[] findArray(String query, Object arg1);
    public Object[] findArray(String query, Object arg1, Object arg2);
    public Object[] findArray(String query, Object arg1, Object arg2, Object arg3);
    */
    public function findArray($query, $arg1 = null, $arg2 = null, $arg3 = null);
    
    /**
    public Object findBean(String query);
    public Object findBean(String query, Object arg1);
    public Object findBean(String query, Object arg1, Object arg2);
    public Object findBean(String query, Object arg1, Object arg2, Object arg3);
    */
    public function findBean($query, $arg1 = null, $arg2 = null, $arg3 = null);
    
    /**
    public Object findObject(String query);
    public Object findObject(String query, Object arg1);
    public Object findObject(String query, Object arg1, Object arg2);
    public Object findObject(String query, Object arg1, Object arg2, Object arg3);
    */
    public function findObject($query, $arg1 = null, $arg2 = null, $arg3 = null);
}
?>
