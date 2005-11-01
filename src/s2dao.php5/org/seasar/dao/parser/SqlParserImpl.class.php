<?php

/**
 * @author nowel
 */
class SqlParserImpl implements SqlParser {

    private $tokenizer_ = null;
    private $nodeStack_;

    public function __construct($sql) {
        $sql = trim($sql);

        $sql = preg_replace("/(.+);$/s", "\\1", $sql);
        $this->tokenizer_ = new SqlTokenizerImpl($sql);
        $this->nodeStack_ = array();
    }

    public function parse() {
        $this->push(new ContainerNode());
        while ($this->tokenizer_->next() != SqlTokenizer::EOF) {
            $this->parseToken();
        }
        return $this->pop();
    }

    protected function parseToken() {
        switch ($this->tokenizer_->getTokenType()) {
        case SqlTokenizer::SQL :
            $this->parseSql();
            break;
        case SqlTokenizer::COMMENT :
            $this->parseComment();
            break;
        case SqlTokenizer::ELSE_ :
            $this->parseElse();
            break;
        case SqlTokenizer::BIND_VARIABLE :
            $this->parseBindVariable();
            break;
        }
    }

    protected function parseSql() {
        $sql = $this->tokenizer_->getToken();
        if ($this->isElseMode()) {
            $sql = str_replace("--", "", $sql);
        }
        $node = $this->peek();
        if (($node instanceof IfNode || $node instanceof ElseNode)
                && $node->getChildSize() == 0) {
            $st = new SqlTokenizerImpl($sql);
            $st->skipWhitespace();
            $token = $st->skipToken();
            $st->skipWhitespace();

            if( eregi("AND",$token) || eregi("OR",$token) ){
                $node->addChild(new PrefixSqlNode($st->getBefore(), $st->getAfter()));
            } else {
                $node->addChild(new SqlNode($sql));
            }
        } else {
            $node->addChild(new SqlNode($sql));
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
        $condition = trim( substr($this->tokenizer_->getToken(), 2) );
        if (empty($condition)) {
            throw new IfConditionNotFoundRuntimeException();
        }
        $ifNode = new IfNode($condition);
        $this->peek()->addChild($ifNode);
        $this->push($ifNode);
        $this->parseEnd();
    }

    protected function parseBegin() {
        $beginNode = new BeginNode();
        $this->peek()->addChild($beginNode);
        $this->push($beginNode);
        $this->parseEnd();
    }

    protected function parseEnd() {
        while (SqlTokenizer::EOF != $this->tokenizer_->next()) {
            if ($this->tokenizer_->getTokenType() == SqlTokenizer::COMMENT
                    && $this->isEndComment($this->tokenizer_->getToken())) {
                $this->pop();
                return;
            }
            $this->parseToken();
        }
        throw new EndCommentNotFoundRuntimeException();
    }

    protected function parseElse() {
        $parent = $this->peek();
        if (!($parent instanceof IfNode)) {
            return;
        }
        $ifNode = $this->pop();
        $elseNode = new ElseNode();
        $ifNode->setElseNode($elseNode);
        $this->push($elseNode);
        $this->tokenizer_->skipWhitespace();
    }

    protected function parseCommentBindVariable() {
        $expr = $this->tokenizer_->getToken();
        $s = $this->tokenizer_->skipToken();
        if ($s !== false && ereg("^\(", $s) && ereg("\)$", $s) ) {
            $this->peek()->addChild(new ParenBindVariableNode($expr));
        } else if (ereg("^\$", $expr)) {
            $this->peek()->addChild(new EmbeddedValueNode(substr($expr,1)));
        } else {
            $this->peek()->addChild(new BindVariableNode($expr));
        }
    }

    protected function parseBindVariable() {
        $expr = $this->tokenizer_->getToken();
        $this->peek()->addChild(new BindVariableNode($expr));
    }

    protected function pop() {
        return array_pop($this->nodeStack_);
    }

    protected function peek() {
        $shift = array_shift($this->nodeStack_);
        array_unshift($this->nodeStack_, $shift);
        return $shift;
    }

    protected function push(Node $node) {
        array_push($this->nodeStack_, $node);
    }

    protected function isElseMode() {
        for ($i = 0; $i < count($this->nodeStack_); ++$i) {
            if ($this->nodeStack_[$i] instanceof ElseNode) {
                return true;
            }
        }
        return false;
    }

    private static function isTargetComment($comment = null) {
        //Character.isJavaIdentifierStart(substr($comment,0,1));
        return $comment != null && strlen($comment) > 0
                && substr($comment,0,1) != null;
    }

    private static function isIfComment($comment) {
        return ereg("^IF", $comment);
    }

    private static function isBeginComment($content = null) {
        return $content != null && "BEGIN" == $content;
    }

    private static function isEndComment($content = null) {
        return $content != null && "END" == $content;
    }
}
?>
