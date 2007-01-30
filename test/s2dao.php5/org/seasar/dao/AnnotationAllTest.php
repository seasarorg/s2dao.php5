<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author nowel
 */
class AnnotationAllTest {
    
    public function __construct(){
    }
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite("All Annotation Tests");
        $suite->addTestSuite('S2Dao_AbstractAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_BeanCommentAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_BeanConstantAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_DaoCommentAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_DaoConstantAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_FieldAnnotationReaderFactoryTest');
        $suite->addTestSuite('ArgumentsTest');
        $suite->addTestSuite('BeanTest');
        $suite->addTestSuite('ColumnTest');
        $suite->addTestSuite('DaoTest');
        $suite->addTestSuite('IdTest');
        $suite->addTestSuite('NoPersistentPropertyTest');
        $suite->addTestSuite('PersistentPropertyTest');
        $suite->addTestSuite('ProcedureTest');
        $suite->addTestSuite('QueryTest');
        $suite->addTestSuite('RelationTest');
        $suite->addTestSuite('SqlTest');
        $suite->addTestSuite('TimestampPropertyTest');
        $suite->addTestSuite('VersionNoPropertyTest');
        return $suite;
    }
}

?>