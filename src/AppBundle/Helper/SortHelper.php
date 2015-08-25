<?php

namespace AppBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class SortHelper {

    // NOTE: They're reversed for a reason, it's not a bug.
    protected $_classAsc = "sorted descending";
    protected $_classDesc = "sorted ascending";

    public $column;
    public $direction;

    public function __construct(Request $req) {
        $this->column = $req->get('sort_column');
        $this->direction = $req->get('sort_direction');
    }

    public function getQuery($column) {
        // Default sort query
        $query = [
            'sort_column' => $column,
            'sort_direction' => 'asc',
        ];
        // In case this column was already sorted
        if ($this->column === $column && $this->direction === 'asc') {
            $query['sort_direction'] = 'desc';
        }
        // Build query
        return http_build_query($query);
    }

    public function getClass($column) {
        if ($this->column !== $column) {
            return null;
        }
        return $this->direction === 'asc'
            ? $this->_classAsc
            : $this->_classDesc;
    }

}
