<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the 'License');      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an 'AS IS' BASIS,    |
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
class S2Dao_SqlTokenizerImplTest extends PHPUnit2_Framework_TestCase {

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite('S2Dao_SqlTokenizerImplTest');
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testNext() {
        $sql = 'SELECT * FROM emp';
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals($sql, $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
        $this->assertEquals(null, $tokenizer->getToken());
    }

    public function testCommentEndNotFound() {
        $sql = 'SELECT * FROM emp/*hoge';
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('SELECT * FROM emp', $tokenizer->getToken());
        try {
            $tokenizer->next();
            $this->fail('3');
        } catch (S2Dao_TokenNotClosedRuntimeException $ex) {
            var_dump($ex->getMessage());
        }
    }

    public function testBindVariable() {
        $sql = "SELECT * FROM emp WHERE job = /*job*/'CLER K' AND deptno = /*deptno*/20";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('SELECT * FROM emp WHERE job = ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('job', $tokenizer->getToken());
        $tokenizer->skipToken();
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals(' AND deptno = ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('deptno', $tokenizer->getToken());
        $tokenizer->skipToken();
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
    }
    
    public function testParseBindVariable2() {
        $sql = "SELECT * FROM emp WHERE job = /*job*/'CLERK'/**/";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('SELECT * FROM emp WHERE job = ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('job', $tokenizer->getToken());
        $tokenizer->skipToken();
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
    }
    
    public function testParseBindVariable3() {
        $sql = "/*job*/'CLERK',";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('job', $tokenizer->getToken());
        $tokenizer->skipToken();
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals(',', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
    }
    
    public function testParseElse() {
        $sql = "SELECT * FROM emp WHERE /*IF job != null*/job = /*job*/'CLERK'--ELSE job is null/*END*/";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('SELECT * FROM emp WHERE ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('IF job != null', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('job = ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('job', $tokenizer->getToken());
        $tokenizer->skipToken();
        $this->assertEquals(S2Dao_SqlTokenizer::ELSE_, $tokenizer->next());
        $tokenizer->skipWhitespace();
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('job is null', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('END', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
    }
    
    public function testParseElse2() {
        $sql = "/*IF false*/aaa --ELSE bbb = /*bbb*/123/*END*/";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('IF false', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('aaa ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::ELSE_, $tokenizer->next());
        $tokenizer->skipWhitespace();
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('bbb = ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('bbb', $tokenizer->getToken());
        $tokenizer->skipToken();
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('END', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
    }
    
    public function testAnd() {
        $sql = "AND bbb";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $whiteSpace = $tokenizer->skipWhitespace();
        $this->assertTrue(empty($whiteSpace));
        $this->assertEquals('AND', $tokenizer->skipToken());
        $this->assertEquals('AND', $tokenizer->getBefore());
        $this->assertEquals(' bbb', $tokenizer->getAfter());
    }
    
    public function testBindVariable2() {
        try {
            $sql = "? abc ? def ?";
            $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
            $this->assertEquals(S2Dao_SqlTokenizer::BIND_VARIABLE, $tokenizer->next());
            $this->assertEquals('$1', $tokenizer->getToken());
            $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
            $this->assertEquals(' abc ', $tokenizer->getToken());
            $this->assertEquals(S2Dao_SqlTokenizer::BIND_VARIABLE, $tokenizer->next());
            $this->assertEquals('$2', $tokenizer->getToken());
            $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
            $this->assertEquals(' def ', $tokenizer->getToken());
            $this->assertEquals(S2Dao_SqlTokenizer::BIND_VARIABLE, $tokenizer->next());
            $this->assertEquals('$3', $tokenizer->getToken());
            $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
        } catch(Exception $e){
            echo $e . PHP_EOL;
        }
    }
    
    public function testBindVariable3() {
        $sql = "abc ? def";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('abc ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::BIND_VARIABLE, $tokenizer->next());
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals(' def', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
    }
    
    public function testBindVariable4() {
        $sql = "/*IF false*/aaa--ELSE bbb = /*bbb*/123/*END*/";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('IF false', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('aaa', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::ELSE_, $tokenizer->next());
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals('bbb = ', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('bbb', $tokenizer->getToken());
    }
    
    public function testSkipTokenForParent() {
        $sql = "INSERT INTO TABLE_NAME (ID) VALUES (/*id*/20)";
        $tokenizer = new S2Dao_SqlTokenizerImpl($sql);
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals(S2Dao_SqlTokenizer::COMMENT, $tokenizer->next());
        $this->assertEquals('20', $tokenizer->skipToken());
        $this->assertEquals(S2Dao_SqlTokenizer::SQL, $tokenizer->next());
        $this->assertEquals(')', $tokenizer->getToken());
        $this->assertEquals(S2Dao_SqlTokenizer::EOF, $tokenizer->next());
    }
}
?>
