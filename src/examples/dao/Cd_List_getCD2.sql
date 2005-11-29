SELECT *
FROM CD
/*BEGIN*/WHERE
    /*IF id != null*/ ID = /*id*/'1'/*END*/
    /*IF title != null*/ AND TITLE = /*title*/'bohemian rhapsody'/*END*/
    /*IF content == "rock"*/ AND CONTENT = 'blues'/*END*/
/*END*/
