<?php
  
/**
 * @auther yonekawa
 */
class PagerViewHelper implements PagerCondition
{
    /** 画面上でのページの最大表示件数のデフォルト  */
    const DEFAULT_DISPLAY_PAGE_MAX = 9;
    
    /** 検索条件オブジェクト */
    private $condition;

    /** 画面上でのページの最大表示件数 */
    private $displayPageMax;

    public function __construct( $condition, $displayPageMax = null )
    {
        $this->condition = $condition;
        
        if ( isset( $displayPageMax ) ) {
            $this->displayPageMax = $displayPageMax;
        } else {
            $this->displayPageMax = self::DEFAULT_DISPLAY_PAGE_MAX;
        }
    }
    
    public function getCount()
    {
        return $this->condition->getCount();
    }
    public function setCount( $count )
    {
        $this->condition->setCount( $count );
    }

    public function getLimit()
    {
        return $this->condition->getLimit();
    }
    public function setLimit( $limit )
    {
        $this->condition->setLimit( $limit );
    }

    public function getOffset()
    {
        return $this->condition->getOffset();
    }
    public function setOffset( $offset )
    {
        $this->condition->setOffset( $offset );
    }

    /**
     * 前へのリンクが表示できるかどうかを判定します。
     */
    public function isPrev() 
    {
        return $this->condition->getOffset() > 0;
    }

    /**
     * 次へのリンクが表示できるかどうかを判定します。
     */
    public function isNext() 
    {
        return $this->condition->getCount() > 0 
            && ( $this->condition->getOffset() + $this->condition->getLimit() 
                 < $this->condition->getCount() );
    }

    /**
     * 現在表示中の一覧の最後のoffsetを取得します。
     */
    public function getCurrentLastOffset() 
    {
        $nextOffset = $this->getNextOffset( $this->condition );
        if ( $nextOffset <= 0 || $this->condition->getCount() <= 0 ) {
            return 0;
        } else {
            return 
                $nextOffset < $this->condition->getCount 
                ? $nextOffset - 1
                : $this->condition->getCount() - 1;            
        }
    }

    /**
     * 次へリンクのoffsetを返します。
     */
    public function getNextOffset() 
    {
        return $this->condition->getOffset() + $this->condition->getLimit();
    }

    /**
     * 前へリンクのoffsetを返します。
     */
    public function getPrevOffset() 
    {
        $prevOffset = $this->condition->getOffset() - $this->condition->getLimit();
        return $prevOffset < 0 ? 0 : $prevOffset;
    }
    
    /**
     * 現在ページのインデックスを返します。
     */
    public function getPageIndex() 
    {
        if ( $this->condition->getLimit() == 0 ) {
            return 1;
        } else {
            return $this->condition->getOffset() / $this->condition->getLimit();
        }
    }

    /**
     * 現在ページのカウント(インデックス+1)を返します。
     */
    public function getPageCount() 
    {
        return $this->getPageIndex() + 1;
    }

    /**
     * 最終ページのインデックスを返します。
     */
    public function getLastPageIndex() 
    {
        if ( $this->condition->getLimit() == 0 ) {
            return 0;
        } else {
            return floor( ( $this->condition->getCount() - 1 ) / $this->condition->getLimit() );
        }
    }

    /**
     * ページリンクの表示上限を元に、ページ番号リンクの表示開始位置を返します。
     */
    public function getDisplayPageIndexBegin() 
    {
        $lastPageIndex = $this->getLastPageIndex();
        if ( $lastPageIndex < $this->displayPageMax ) {
            return 0;
        } else {
            $currentPageIndex = $this->getPageIndex();
            $displayPageIndexBegin = $currentPageIndex
                - ( floor( $displayPageMax / 2 ) );
            return $displayPageIndexBegin < 0 ? 0 : $displayPageIndexBegin;
        }
    }

    /**
     * ページリンクの表示上限を元に、ページ番号リンクの表示終了位置を返します。
     */
    public function getDisplayPageIndexEnd() {
        $lastPageIndex = $this->getLastPageIndex();
        $displayPageIndexBegin = $this->getDisplayPageIndexBegin();
        $displayPageRange = $lastPageIndex - $displayPageIndexBegin;
        if ( $displayPageRange < $this->displayPageMax ) {
            return $lastPageIndex;
        } else {
            return $displayPageIndexBegin + $this->displayPageMax - 1;
        }
    }

    /*
    public function printLinkTag()
    {
        $prev_tag = '<a href="' . $this->pageUrl . '?offset=' . $this->getPrevOffset() . '">' 
            . '前の' . $this->getLimit() . '件</a>　';
        $next_tag = '　<a href="' . $this->pageUrl . '?offset=' . $this->getNextOffset() . '">' 
            . '次の' . $this->getLimit() . '件</a>　';
    
        if ( $this->isPrev() ) {
            print( $prev_tag ); 
        }
        
        for ( $i = $this->getDisplayPageIndexBegin(); $i <= $this->getDisplayPageIndexEnd(); $i++ ) {
            
            $page_number_tag = 
                '<a href="' . $this->pageUrl . '?offset=' . $i * $this->getLimit() . '">'
                . ( $i + 1 ) . '</a> ';

            if ( $i == $this->getPageIndex() ) {
                $page_number_tag = ( $i + 1 ) . ' ';
            }
            print( $page_number_tag );
        }
        
        if ( $this->isNext() ) {
            print( $next_tag );
        }
    }
    */
}

?>