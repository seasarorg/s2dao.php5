<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_SqlParserImplTest extends PHPUnit2_Framework_TestCase {

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_SqlParserImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testParse() {
        $sql = "SELECT * FROM EMP2 emp2";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $node = $parser->parse();
        $node->accept($ctx);
        $this->assertEquals($sql, $ctx->getSql());
    }

    public function testParseEndSemicolon() {
        $this->parseEndSemicolon2(";");
        $this->parseEndSemicolon2(";\t");
        $this->parseEndSemicolon2("; ");
    }
    
    private function parseEndSemicolon2($endChar) {
        $sql = "SELECT * FROM EMP2 emp2";
        $parser = new S2Dao_SqlParserImpl($sql . $endChar);
        $ctx = new S2Dao_CommandContextImpl();
        $node = $parser->parse();
        $node->accept($ctx);
        $this->assertEquals($sql, $ctx->getSql());
    }
        
    public function testCommentEndNotFound() {
        $sql = "SELECT * FROM EMP2 emp2/*hoge";
        $parser = new S2Dao_SqlParserImpl($sql);
        try {
            $parser->parse();
            $this->fail("1");
        } catch (S2Dao_TokenNotClosedRuntimeException $ex) {
            var_dump('[message]: ' . $ex->getMessage());
        }
    }

    public function testParseBindVariable4() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE job = #*job*#'CLERK' AND deptno = #*deptno*#20";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $job = "CLERK";
        $deptno = 20;
        $ctx->addArg("job", $job, gettype($job));
        $ctx->addArg("deptno", $deptno, gettype($deptno));
        $root = $parser->parse();
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
    }
    
    public function testParseBindVariable() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE job = /*job*/'CLERK' AND deptno = /*deptno*/20";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE job = ? AND deptno = ?";
        $sql3 = "SELECT * FROM EMP2 emp2 WHERE job = ";
        $sql4 = " AND deptno = ";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $job = "CLERK";
        $deptno = 20;
        $ctx->addArg("job", $job, gettype($job));
        $ctx->addArg("deptno", $deptno, gettype($deptno));
        $root = $parser->parse();
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(2, count($vars));
        $this->assertEquals($job, $vars[0]);
        $this->assertEquals($deptno, $vars[1]);
        $this->assertEquals(4, $root->getChildSize());
        $sqlNode = $root->getChild(0);
        $this->assertEquals($sql3, $sqlNode->getSql());
        $varNode = $root->getChild(1);
        $this->assertEquals("job", $varNode->getExpression());
        $sqlNode2 = $root->getChild(2);
        $this->assertEquals($sql4, $sqlNode2->getSql());
        $varNode2 = $root->getChild(3);
        $this->assertEquals("deptno", $varNode2->getExpression());
    }
    
    public function testParseBindVariable2() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE job = /* job*/'CLERK'";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE job = 'CLERK'";
        $sql2_parsed = "SELECT * FROM EMP2 emp2 WHERE job = ?";
        $sql3 = "SELECT * FROM EMP2 emp2 WHERE job = ";
        $sql4 = " job";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $root = $parser->parse();
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2_parsed, $ctx->getSql());
        $this->assertEquals(2, $root->getChildSize());
        $sqlNode = $root->getChild(0);
        $this->assertEquals($sql3, $sqlNode->getSql());
        $sqlNode2 = $root->getChild(1);
        $this->assertEquals($sql4, $sqlNode2->getExpression());
    }
    
    public function testParseWhiteSpace() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE emp2no = /*emp2no*/1 AND 1 = 1";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE emp2no = ? AND 1 = 1";
        $sql3 = " AND 1 = 1";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $emp2no = 7788;
        $ctx->addArg("emp2no", $emp2no, gettype($emp2no));
        $root = $parser->parse();
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        $sqlNode = $root->getChild(2);
        $this->assertEquals($sql3, $sqlNode->getSql());
    }
    
    public function testParseIf() {
        $sql = "SELECT * FROM EMP2 emp2/*IF job != null*/ WHERE job = /*job*/'CLERK'/*END*/";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE job = ?";
        $sql3 = "SELECT * FROM EMP2 emp2";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $job = "CLERK";
        $ctx->addArg("job", $job, gettype($job));
        $root = $parser->parse();
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(1, count($vars));
        $this->assertEquals($job, $vars[0]);
        $this->assertEquals(2, $root->getChildSize());
        $sqlNode = $root->getChild(0);
        $this->assertEquals($sql3, $sqlNode->getSql());
        $ifNode = $root->getChild(1);
        $this->assertEquals("job != null", $ifNode->getExpression());
        $this->assertEquals(2, $ifNode->getChildSize());
        $sqlNode2 = $ifNode->getChild(0);
        $this->assertEquals(" WHERE job = ", $sqlNode2->getSql());
        $varNode = $ifNode->getChild(1);
        $this->assertEquals("job", $varNode->getExpression());
        $ctx2 = new S2Dao_CommandContextImpl();
        $root->accept($ctx2);
        echo $ctx2->getSql() , PHP_EOL;
        $this->assertEquals($sql3, $ctx2->getSql());
    }
    
    public function testParseIf2() {
        $sql = "/*IF aaa != null*/aaa/*IF bbb != null*/bbb/*END*//*END*/";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $root = $parser->parse();
        $root->accept($ctx);
        echo "[" . $ctx->getSql() . "]" , PHP_EOL;
        $this->assertEquals("", $ctx->getSql());
        $ctx->addArg("aaa", null, gettype(""));
        $ctx->addArg("bbb", "hoge",gettype(""));
        $root->accept($ctx);
        echo "[" . $ctx->getSql() . "]" , PHP_EOL;
        $this->assertEquals("", $ctx->getSql());
        $ctx->addArg("aaa", "hoge", gettype(""));
        $root->accept($ctx);
        echo "[" . $ctx->getSql() . "]" , PHP_EOL;
        $this->assertEquals("aaabbb", $ctx->getSql());
        $ctx2 = new S2Dao_CommandContextImpl();
        $ctx2->addArg("aaa", "hoge", gettype(""));
        $ctx2->addArg("bbb", null, gettype(""));
        $root->accept($ctx2);
        echo "[" . $ctx2->getSql() . "]" , PHP_EOL;
        $this->assertEquals("aaa", $ctx2->getSql());
    }
    
    public function testParseElse() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE /*IF job != null*/job = /*job*/'CLERK' --ELSE job is null/*END*/";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE job = ? ";
        $sql3 = "SELECT * FROM EMP2 emp2 WHERE job is null";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $job = "CLERK";
        $ctx->addArg("job", $job, gettype($job));
        $root = $parser->parse();
        $root->accept($ctx);
        echo "[" . $ctx->getSql() . "]" , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(1, count($vars));
        $this->assertEquals($job, $vars[0]);
        $ctx2 = new S2Dao_CommandContextImpl();
        $root->accept($ctx2);
        echo "[" . $ctx2->getSql() . "]" , PHP_EOL;
        $this->assertEquals($sql3, $ctx2->getSql());
    }
    
    public function testParseElse2() {
        $sql = "/*IF false*/aaa--ELSE bbb = /*bbb*/123/*END*/";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $bbb = 123;
        $ctx->addArg("bbb", $bbb, gettype($bbb));
        $root = $parser->parse();
        $root->accept($ctx);
        echo "[" . $ctx->getSql() . "]" , PHP_EOL;
        $this->assertEquals("bbb = ?", $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(1, count($vars));
        $this->assertEquals($bbb, $vars[0]);
    }
    
    public function testParseElse3() {
        $sql = "/*IF false*/aaa--ELSE bbb/*IF false*/ccc--ELSE ddd/*END*//*END*/";
        $parser = new S2Dao_SqlParserImpl($sql);
        $ctx = new S2Dao_CommandContextImpl();
        $root = $parser->parse();
        $root->accept($ctx);
        echo "[" . $ctx->getSql() . "]" , PHP_EOL;
        $this->assertEquals("bbbddd", $ctx->getSql());
    }
    
    public function testElse4() {
        $sql = "SELECT * FROM EMP2 emp2/*BEGIN*/ WHERE /*IF false*/aaa--ELSE AND deptno = 10/*END*//*END*/";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE AND deptno = 10";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
    }
    
    public function testBegin() {
        $sql = "SELECT * FROM EMP2 emp2/*BEGIN*/ WHERE /*IF job !== null*/job = /*job*/'CLERK'/*END*//*IF deptno !== null*/ AND deptno = /*deptno*/20/*END*//*END*/";
        $sql2 = "SELECT * FROM EMP2 emp2";
        $sql3 = "SELECT * FROM EMP2 emp2 WHERE job = ?";
        $sql4 = "SELECT * FROM EMP2 emp2 WHERE job = ? AND deptno = ?";
        $sql5 = "SELECT * FROM EMP2 emp2 WHERE  AND deptno = ?";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        
        $ctx2 = new S2Dao_CommandContextImpl();
        $ctx2->addArg("job", "CLERK", gettype(""));
        $ctx2->addArg("deptno", null, gettype(0));
        $root->accept($ctx2);
        echo $ctx2->getSql() , PHP_EOL;
        $this->assertEquals($sql3, $ctx2->getSql());
        
        $ctx3 = new S2Dao_CommandContextImpl();
        $ctx3->addArg("job", "CLERK", gettype(""));
        $ctx3->addArg("deptno", 20, gettype(20));
        $root->accept($ctx3);
        echo $ctx3->getSql() , PHP_EOL;
        $this->assertEquals($sql4, $ctx3->getSql());
        
        $ctx4 = new S2Dao_CommandContextImpl();
        $ctx4->addArg("deptno", 20, gettype(20));
        $ctx4->addArg("job", null, gettype(""));
        $root->accept($ctx4);
        echo $ctx4->getSql() , PHP_EOL;
        $this->assertEquals($sql5, $ctx4->getSql());
    }
    
    public function testBeginAnd() {
        $sql = "/*BEGIN*/WHERE /*IF true*/aaa BETWEEN /*bbb*/111 AND /*ccc*/123/*END*//*END*/";
        $sql2 = "WHERE aaa BETWEEN ? AND ?";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $ctx->addArg("bbb", "111", gettype("111"));
        $ctx->addArg("ccc", "222", gettype("222"));
        $root->accept($ctx);
        echo "[" . $ctx->getSql() . "]" , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
    }
    
    public function testIn() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE deptno IN /*deptnoList*/(10, 20) ORDER BY ename";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE deptno IN (?, ?) ORDER BY ename";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $deptnoList = new S2Dao_ArrayList();
        $deptnoList->add(10);
        $deptnoList->add(20);
        $ctx->addArg("deptnoList", $deptnoList, gettype($deptnoList->toArray()));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(1, count($vars[0]));
        $this->assertEquals(10, $vars[0]);
    }
    
    public function testIn2() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE deptno IN /*deptnoList*/(10, 20) ORDER BY ename";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE deptno IN (?, ?) ORDER BY ename";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $deptnoArray = new S2Dao_ArrayList(array(10, 20));
        $ctx->addArg("deptnoList", $deptnoArray, gettype($deptnoArray));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(1, count($vars[0]));
        $this->assertEquals(10, $vars[0]);
    }
    
    public function testIn3() {
        $sql = "SELECT * FROM EMP2 emp2 WHERE ename IN /*enames*/('SCOTT','MARY') AND job IN /*jobs*/('ANALYST', 'FREE')";
        $sql2 = "SELECT * FROM EMP2 emp2 WHERE ename IN (?, ?) AND job IN (?, ?)";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $enames = new S2Dao_ArrayList(array("SCOTT", "MARY"));
        $jobs = new S2Dao_ArrayList(array("ANALYST", "FREE"));
        $ctx->addArg("enames", $enames, gettype($enames));
        $ctx->addArg("jobs", $jobs, gettype($jobs));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql2, $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(1, count($vars[0]));
        $this->assertEquals("SCOTT", $vars[0]);
        $this->assertEquals("MARY", $vars[1]);
    }
    
    public function testParseBindVariable3() {
        $sql = "BETWEEN sal ? AND ?";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $ctx->addArg('$1', 0, gettype(0));
        $ctx->addArg('$2', 1000, gettype(1000));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals($sql, $ctx->getSql());
        $vars = $ctx->getBindVariables();
        $this->assertEquals(2, count($vars));
        $this->assertEquals(0, $vars[0]);
        $this->assertEquals(1000, $vars[1]);
    }
    
    public function testEndNotFound() {
        $sql = "/*BEGIN*/";
        $parser = new S2Dao_SqlParserImpl($sql);
        try {
            $parser->parse();
            $this->fail("1");
        } catch (S2Dao_EndCommentNotFoundRuntimeException $ex) {
            echo $ex , PHP_EOL;
        }
    }
    
    public function testEndParent() {
        $sql = "INSERT INTO ITEM (ID, NUM) VALUES (/*id*/1, /*num*/20)";
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $ctx->addArg("id", 0, gettype(0));
        $ctx->addArg("num", 1, gettype(1));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals(true, preg_match('/\)$/', $ctx->getSql()) == 1);
    }
    
    public function testEmbeddedValue() {
        $sql = '/*$aaa*/';
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        $ctx = new S2Dao_CommandContextImpl();
        $ctx->addArg("aaa", 0, gettype(0));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals("", $ctx->getSql());
    }
    
    public function testDAOPHP8_1(){
        $sql = '/*IF weight != null*/formats.weight = /*weight*/0 --ELSE weight is null/*END*/';
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        // string null
        $ctx = new S2Dao_CommandContextImpl();
        $ctx->addArg('weight', '', gettype(null));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals('weight is null', $ctx->getSql());
        // string value
        $ctx2 = new S2Dao_CommandContextImpl();
        $ctx2->addArg('weight', 'hoge', gettype(''));
        $root->accept($ctx2);
        echo $ctx2->getSql() , PHP_EOL;
        $this->assertEquals('formats.weight = ? ', $ctx2->getSql());
        // null string
        $ctx3 = new S2Dao_CommandContextImpl();
        $ctx3->addArg('weight', null, gettype(''));
        $root->accept($ctx3);
        echo $ctx3->getSql() , PHP_EOL;
        $this->assertEquals('weight is null', $ctx3->getSql());
        // integer value
        $ctx4 = new S2Dao_CommandContextImpl();
        $ctx4->addArg('weight', 100, gettype(100));
        $root->accept($ctx4);
        echo $ctx4->getSql() , PHP_EOL;
        $this->assertEquals('formats.weight = ? ', $ctx4->getSql());
    }
    
    public function testDAOPHP8_2(){
        $sql = 'SELECT * FROM formats WHERE /*IF weight != null*/formats.weight = /*weight*/0 --ELSE weight is null/*END*/';
        $parser = new S2Dao_SqlParserImpl($sql);
        $root = $parser->parse();
        // correct
        $ctx = new S2Dao_CommandContextImpl();
        $ctx->addArg('weight', 100, gettype(100));
        $root->accept($ctx);
        echo $ctx->getSql() , PHP_EOL;
        $this->assertEquals('SELECT * FROM formats WHERE formats.weight = ? ', $ctx->getSql());
        // un
        $ctx2 = new S2Dao_CommandContextImpl();
        $ctx2->addArg('weight', null, gettype(null));
        $root->accept($ctx2);
        echo $ctx2->getSql() , PHP_EOL;
        $this->assertEquals('SELECT * FROM formats WHERE weight is null', $ctx2->getSql());
    }
}
?>
