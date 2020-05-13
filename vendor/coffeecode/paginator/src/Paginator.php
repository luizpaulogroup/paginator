<?php

namespace CoffeeCode\Paginator;

/**
 * Class CoffeeCode Paginator
 *
 * @author Robson V. Leite <https://github.com/robsonvleite>
 * @package CoffeeCode\Paginator
 */
class Paginator
{

    /** @var int */
    private $page;

    /** @var int */
    private $pages;

    /** @var int */
    private $rows;

    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    /** @var int */
    private $range;

    /** @var string */
    private $link;

    /** @var string */
    private $title;

    /** @var string */
    private $class;

    /** @var string */
    private $hash;

    /** @var array */
    private $first;

    /** @var array */
    private $last;

    /**
     * Paginator constructor.
     * @param string|null $link
     * @param string|null $title
     * @param array|null $first
     * @param array|null $last
     */
    public function __construct(string $link = null, string $title = null, array $first = null, array $last = null)
    {
        $this->link = ($link ? $link : "?page=");
        $this->title = ($title ? $title : "Página");
        $this->first = ($first ? $first : ["Primeira página", "<<"]);
        $this->last = ($last ? $last : ["Última página", ">>"]);
    }

    /**
     * @param int $rows
     * @param int $limit
     * @param int|null $page
     * @param int $range
     * @param string|null $hash
     */
    public function pager(int $rows, int $limit = 10, int $page = null, int $range = 3, string $hash = null)
    {
        $this->rows = $this->toPositive($rows);
        $this->limit = $this->toPositive($limit);
        $this->range = $this->toPositive($range);
        $this->pages = (int) ceil($this->rows / $this->limit);
        $this->page = ($page <= $this->pages ? $this->toPositive($page) : $this->pages);

        $this->offset = (($this->page * $this->limit) - $this->limit >= 0 ? ($this->page * $this->limit) - $this->limit : 0);
        $this->hash = (!empty($hash) ? "#{$hash}" : null);

        if ($this->rows && $this->offset >= $this->rows) {
            header("Location: {$this->link}" . ceil($this->rows / $this->limit));
            exit;
        }
    }

    /**
     * @return int
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function pages()
    {
        return $this->pages;
    }

    /**
     * @param string $cssClass
     * @return null|string
     */
    public function render(string $cssClass = "paginator")
    {
        $this->class = $cssClass;

        if ($this->rows > $this->limit):
            $paginator = "<nav class=\"{$this->class}\">";
            $paginator .= "<a class='{$this->class}_item' title=\"{$this->first[0]}\" href=\"{$this->link}1{$this->hash}\">{$this->first[1]}</a>";
            $paginator .= $this->beforePages();
            $paginator .= "<span class=\"{$this->class}_item {$this->class}_active\">{$this->page}</span>";
            $paginator .= $this->afterPages();
            $paginator .= "<a class='{$this->class}_item' title=\"{$this->last[0]}\" href=\"{$this->link}{$this->pages}{$this->hash}\">{$this->last[1]}</a>";
            $paginator .= "</nav>";
            return $paginator;
        endif;

        return null;
    }

    /**
     * @return null|string
     */
    private function beforePages()
    {
        $before = null;
        for ($iPag = $this->page - $this->range; $iPag <= $this->page - 1; $iPag++):
            if ($iPag >= 1):
                $before .= "<a class='{$this->class}_item' title=\"{$this->title} {$iPag}\" href=\"{$this->link}{$iPag}{$this->hash}\">{$iPag}</a>";
            endif;
        endfor;

        return $before;
    }

    /**
     * @return string|null
     */
    private function afterPages()
    {
        $after = null;
        for ($dPag = $this->page + 1; $dPag <= $this->page + $this->range; $dPag++):
            if ($dPag <= $this->pages):
                $after .= "<a class='{$this->class}_item' title=\"{$this->title} {$dPag}\" href=\"{$this->link}{$dPag}{$this->hash}\">{$dPag}</a>";
            endif;
        endfor;

        return $after;
    }

    /**
     * @param $number
     * @return int
     */
    private function toPositive($number)
    {
        return ($number >= 1 ? $number : 1);
    }

}
