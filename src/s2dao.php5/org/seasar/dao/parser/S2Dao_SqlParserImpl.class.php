<?php

/**
 * @author nowel
 */
class S2Dao_SqlParserImpl implements S2Dao_SqlParser {

    private $tokenizer_ = null;
    private $nodeStack_ = array();

    public function __construct($sql) {
        $sql = trim($sql);
        $sql = preg_replace('/(.+);$/s', '\1', $sql);
        $this->tokenizer_ = new S2Dao_SqlTokenizerImpl($sql);
    }

    public function parse() {
        $this->push(new S2Dao_ContainerNode());
        while ($this->tokenizer_->next() != S2Dao_SqlTokenizer::EOF) {
            $this->parseToken();
        }
        return $this->pop();
    }

    protected function parseToken() {
        switch ($this->tokenizer_->getTokenType()) {
        case S2Dao_SqlTokenizer::SQL :
            $this->parseSql();
            break;
        case S2Dao_SqlTokenizer::COMMENT :
            $this->parseComment();
            break;
        case S2Dao_SqlTokenizer::ELSE_ :
            $this->parseElse();
            break;
        case S2Dao_SqlTokenizer::BIND_VARIABLE :
            $this->parseBindVariable();
            break;
        }
    }

    protected function parseSql() {
        $sql = $this->tokenizer_->getToken();
        if ($this->isElseMode()) {
            $sql = str_replace('--', '', $sql);
        }
        $node = $this->peek();
        if (($node instanceof S2Dao_IfNode || $node instanceof S2Dao_ElseNode)
                && $node->getChildSize() == 0) {
            $st = new S2Dao_SqlTokenizerImpl($sql);
            $st->skipWhitespace();
            $token = $st->skipToken();
            $st->skipWhitespace();

            if(preg_match('/(AND|OR)/i', $token)){
                $node->addChild(new S2Dao_PrefixSqlNode($st->getBefore(), $st->getAfter()));
            } else {
                $node->addChild(new S2Dao_SqlNode($sql));
            }
        } else {
            $node->addChild(new S2Dao_SqlNode($sql));
        }
    }

    protected function parseComment() {
        $comment = $this->tokenizer_->getToken();
        if ($this->isTargetComment($comment)) {
            if ($this->isIfComment($comment)) {
                $this->parseIf();
            } else if ($this->isBeginComment($comment)) {
                $this->parseBegin();
            } else if ($this->isEndComment($comment)) {
                return;
            } else {
                $this->parseCommentBindVariable();
            }
        }
    }

    protected function parseIf() {
        $condition = trim(substr($this->tokenizer_->getToken(), 2));
        if (empty($condition)) {
            throw new S2Dao_IfConditionNotFoundRuntimeException();
        }
        $ifNode = new S2Dao_IfNode($condition);
        $this->peek()->addChild($ifNode);
        $this->push($ifNode);
        $this->parseEnd();
    }

    protected function parseBegin() {
        $beginNode = new S2Dao_BeginNode();
        $this->peek()->addChild($beginNode);
        $this->push($beginNode);
        $this->parseEnd();
    }

    protected function parseEnd() {
        while (S2Dao_SqlTokenizer::EOF != $this->tokenizer_->next()) {
            if ($this->tokenizer_->getTokenType() == S2Dao_SqlTokenizer::COMMENT
                    && $this->isEndComment($this->tokenizer_->getToken())) {
                $this->pop();
                return;
            }
            $this->parseToken();
        }
        throw new S2Dao_EndCommentNotFoundRuntimeException();
    }

    protected function parseElse() {
        $parent = $this->peek();
        if (!($parent instanceof S2Dao_IfNode)) {
            return;
        }
        $ifNode = $this->pop();
        $elseNode = new S2Dao_ElseNode();
        $ifNode->setElseNode($elseNode);
        $this->push($elseNode);
        $this->tokenizer_->skipWhitespace();
    }

    protected function parseCommentBindVariable() {
        $expr = $this->tokenizer_->getToken();
        $s = $this->tokenizer_->skipToken();
        if ($s !== false && ereg('^\(', $s) && ereg('\)$', $s) ) {
            $this->peek()->addChild(new S2Dao_ParenBindVariableNode($expr));
        } else if (ereg('^\$', $expr)) {
            $this->peek()->addChild(new S2Dao_EmbeddedValueNode(substr($expr, 1)));
        } else {
            $this->peek()->addChild(new S2Dao_BindVariableNode($expr));
        }
    }

    protected function parseBindVariable() {
        $expr = $this->tokenizer_->getToken();
        $this->peek()->addChild(new S2Dao_BindVariableNode($expr));
    }

    protected function pop() {
        return array_pop($this->nodeStack_);
    }

    protected function peek() {
        $st = $this->nodeStack_;
        $shift = array_pop($st);
        return $shift;
    }

    protected function push(S2Dao_Node $node) {
        $this->nodeStack_[] = $node;
    }

    protected function isElseMode() {
        $c = count($this->nodeStack_);
        for ($i = 0; $i < $c; ++$i) {
            if ($this->nodeStack_[$i] instanceof S2Dao_ElseNode) {
                return true;
            }
        }
        return false;
    }

    private static function isTargetComment($comment = null) {
        return $comment !== null &&
               0 < strlen($comment) && 
               substr($comment, 0, 1) !== null;
    }

    private static function isIfComment($comment) {
        return ereg('^IF', $comment);
    }

    private static function isBeginComment($content = null) {
        return $content != null && strcmp('BEGIN', $content) == 0;
    }

    private static function isEndComment($content = null) {
        return $content != null && strcmp('END', $content) == 0;
    }
}
?>
