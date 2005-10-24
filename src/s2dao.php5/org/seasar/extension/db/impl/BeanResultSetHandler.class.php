<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.extension.db.impl
 * @author klove
 */
class BeanResultSetHandler extends AbstractBeanResultSetHandler {

    public function BeanResultSetHandler($beanClass) {
        parent::__construct($beanClass);
    }

    public function handle($row){
        $bean = $this->getBeanDesc()->newInstance(array());

        foreach($row as $key=>$val){
            $propName = $this->col2Property($key);
            if($this->getBeanDesc()->hasPropertyDesc($propName)){
                $prop = $this->getBeanDesc()->getPropertyDesc($propName);
                $prop->setValue($bean,$val);
            }
        }
        return $bean;
    }
}
?>