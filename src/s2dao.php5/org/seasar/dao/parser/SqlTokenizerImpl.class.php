<?php

/**
 * @author nowel
 */
class SqlTokenizerImpl implements SqlTokenizer {

    private $sql_ = "";
    private $position_ = 0;
    private $token_ = null;
    private $tokenType_ = self::SQL;
    private $nextTokenType_ = self::SQL;
    private $bindVariableNum = 0;

    public function __construct($sql) {
        $this->sql_ = $sql;
    }

    public function getPosition() {
        return $this->position_;
    }

    public function getToken() {
        return $this->token_;
    }

    public function getBefore() {
        return substr($this->sql_, 0, $this->position_);
    }

    public function getAfter() {
        return substr($this->sql_, $this->position_);
    }

    public function getTokenType() {
        return $this->tokenType_;
    }

    public function getNextTokenType() {
        return $this->nextTokenType_;
    }

    public function next() {
        if ($this->position_ >= strlen($this->sql_)) {
            $this->token_ = null;
            $this->tokenType_ = self::EOF;
            $this->nextTokenType_ = self::EOF;
            return $this->tokenType_;
        }
        switch ($this->nextTokenType_) {
        case self::SQL:
            $this->parseSql();
            break;
        case self::COMMENT:
            $this->parseComment();
            break;
        case self::ELSE_:
            $this->parseElse();
            break;
        case self::BIND_VARIABLE:
            $this->parseBindVariable();
            break;
        default:
            $this->parseEof();
            break;
        }
        return $this->tokenType_;
    }

    protected function parseSql() {
        $commentStartPos = strpos($this->sql_, '/*', $this->position_);
        $lineCommentStartPos = strpos($this->sql_, '--', $this->position_);
        $bindVariableStartPos = strpos($this->sql_, '?', $this->position_);
        $elseCommentStartPos = -1;
        $elseCommentLength = -1;
        if ($lineCommentStartPos !== false && 0 <= $lineCommentStartPos) {
            $skipPos = $this->skipWhitespace($lineCommentStartPos + 2);
            if ($skipPos + 4 < strlen($this->sql_)
                    && 'ELSE' === substr($this->sql_, $skipPos, $skipPos + 4)) {
                $elseCommentStartPos = $lineCommentStartPos;
                $elseCommentLength = $skipPos + 4 - $lineCommentStartPos;
            }
        }
        $nextStartPos = $this->getNextStartPos($commentStartPos,
                                               $elseCommentStartPos,
                                               $bindVariableStartPos);

        if ($nextStartPos === false || $nextStartPos < 0) {
            $this->token_ = substr($this->sql_, $this->position_);
            $nextTokenType_ = self::EOF;
            $this->position_ = strlen($this->sql_);
            $this->tokenType_ = self::SQL;
        } else {
            $endPos = $nextStartPos - $this->position_;
            $this->token_ = substr($this->sql_, $this->position_, $endPos);
            $this->tokenType_ = self::SQL;
            $needNext = $nextStartPos == $this->position_;
            
            if ($nextStartPos == $commentStartPos) {
                $this->nextTokenType_ = self::COMMENT;
                $this->position_ = $commentStartPos + 2;
            } else if ($nextStartPos == $elseCommentStartPos) {
                $this->nextTokenType_ = self::ELSE_;
                $this->position_ = $elseCommentStartPos + $elseCommentLength;
            } else if ($bindVariableStartPos !== false && 
                        $nextStartPos == $bindVariableStartPos) {
                $this->nextTokenType_ = self::BIND_VARIABLE;
                $this->position_ = $bindVariableStartPos;
            }
            if ($needNext) {
                $this->next();
            }
        }
    }

    protected function getNextStartPos($commentStartPos,
                                         $elseCommentStartPos,
                                         $bindVariableStartPos) {

        $nextStartPos = -1;
        if ($commentStartPos !== false && $commentStartPos >= 0) {
            $nextStartPos = $commentStartPos;
        }
        if ($elseCommentStartPos >= 0
                && ($nextStartPos < 0 || $elseCommentStartPos < $nextStartPos)) {
            $nextStartPos = $elseCommentStartPos;
        }
        if ($bindVariableStartPos !== false && $bindVariableStartPos >= 0
                && ($nextStartPos < 0 || $bindVariableStartPos < $nextStartPos)) {
            $nextStartPos = $bindVariableStartPos;
        }
        return $nextStartPos;
    }

    protected function nextBindVariableName() {
        return '$' . ++$this->bindVariableNum;
    }

    protected function parseComment() {
        $commentEndPos = strpos($this->sql_, '*/', $this->position_);
        if ($commentEndPos === false || $commentEndPos < 0) {
            throw new TokenNotClosedRuntimeException('*/',
                                                     substr($this->sql_,
                                                     $this->position_));
        }
        $endPos = $commentEndPos - $this->position_;
        $this->token_ = substr($this->sql_, $this->position_, $endPos);
        $this->nextTokenType_ = self::SQL;
        $this->position_ = $commentEndPos + 2;
        $this->tokenType_ = self::COMMENT;
    }

    protected function parseBindVariable() {
        $this->token_ = $this->nextBindVariableName();
        $this->nextTokenType_ = self::SQL;
        $this->position_ += 1;
        $this->tokenType_ = self::BIND_VARIABLE;
    }

    protected function parseElse() {
        $this->token_ = null;
        $this->nextTokenType_ = self::SQL;
        $this->tokenType_ = self::ELSE_;
    }

    protected function parseEof() {
        $this->token_ = null;
        $this->tokenType_ = self::EOF;
        $this->nextTokenType_ = self::EOF;
    }

    public function skipToken() {
        $index = strlen($this->sql_);
        $quote = $this->position_ < strlen($this->sql_) ?
             substr($this->sql_, $this->position_, 1) : "\0";
        $quoting = $quote === '\'' || $quote === '(';
        if ($quote === '(') {
            $quote = ')';
        }
        $i = $quoting ? ($this->position_ + 1) : $this->position_;
        for (; $i < strlen($this->sql_); ++$i) {
            $c = substr($this->sql_, $i, 1);
            
            if ((preg_match("/(\s|,|\)|\()/", $c) > 0 ) && !$quoting) {
                $index = $i;
                break;
            } else if ($c == '/' && ($i + 1) < strlen($this->sql_)
                        && substr($this->sql_, ($i + 1), 1) == '*') {
                $index = $i;
                break;
            } else if ($c == '-' && ($i + 1) < strlen($this->sql_)
                        && substr($this->sql_, ($i + 1), 1) == '-') {
                $index = $i;
                break;
            } else if ($quoting && $quote == '\'' && $c == '\''
                        && ( ($i + 1) >= strlen($this->sql_) ||
                            substr($this->sql_, ($i + 1), 1) != '\'') ) {
                $index = $i + 1;
                break;
            } else if ($quoting && $c == $quote) {
                $index = $i + 1;
                break;
            }
        }
        $tok = substr($this->sql_, $this->position_, $index - $this->position_);
        $this->token_ = $tok;
        $this->tokenType_ = self::SQL;
        $this->nextTokenType_ = self::SQL;
        $this->position_ = $index;
        return $this->token_;
    }

    public function skipWhitespace($position = null) {
        if($position == null){
            $index = $this->skipWhitespace($this->position_);
            $this->token_ = substr($this->sql_, $this->position_, $index);
            $this->position_ = $index;
            return $this->token_;
        } else {
            $index = strlen($this->sql_);
            for ($i = $position; $i < strlen($this->sql_); ++$i) {
                $c = substr($this->sql_, $i, 1);
                if (!preg_match('/\s/', $c) == 1) {
                    $index = $i;
                    break;
                }
            }
            return $index;
        }
    }
}
?>
