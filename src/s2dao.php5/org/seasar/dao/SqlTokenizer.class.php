<?php

/**
 * @author nowel
 */
interface SqlTokenizer {

    const SQL = 1;
    const COMMENT = 2;
    const ELSE_ = 3;
    const BIND_VARIABLE = 4;
    const EOF = 99;

    public function getToken();

    public function getBefore();

    public function getAfter();

    public function getPosition();

    public function getTokenType();

    public function getNextTokenType();

    public function next();

    public function skipToken();

    public function skipWhitespace();
}
?>
