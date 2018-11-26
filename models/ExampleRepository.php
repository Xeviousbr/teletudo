<?php
use Mgallegos\LaravelJqgrid\Repositories\RepositoryInterface;

class ExampleRepository implements RepositoryInterface {

    /**
     * Calculate the number of rows. It's used for paging the result.
     *
     * @param    array $filters
     *  An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
     *  The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
     *  The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
     *  when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
     *  The 'data' key will contain the string searched by the user.
     * @return  integer
     *  Total number of rows
     */
    public function getTotalNumberOfRows(array $filters = array())
    {
        return 5;
    }

    /**
     * Get the rows data to be shown in the grid.
     *
     * @param    integer $limit
     *  Number of rows to be shown into the grid
     * @param    integer $offset
     *  Start position
     * @param    string $orderBy
     *  Column name to order by.
     * @param    array $sord
     *  Sorting order
     * @param    array $filters
     *  An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
     *  The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
     *  The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
     *  when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
     *  The 'data' key will contain the string searched by the user.
     * @return  array
     *  An array of array, each array will have the data of a row.
     *  Example: array(array("column1" => "1-1", "column2" => "1-2"), array("column1" => "2-1", "column2" => "2-2"))
     */
    public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array())
    {
        return array(
            array("column1" => "1-1", "column2" => "1-2", "column3" => "1-3", "column4" => "1-4", "column5" => "1-5"),
            array("column1" => "2-1", "column2" => "2-2", "column3" => "2-3", "column4" => "2-4", "column5" => "2-5"),
            array("column1" => "3-1", "column2" => "3-2", "column3" => "3-3", "column4" => "3-4", "column5" => "3-5"),
            array("column1" => "4-1", "column2" => "4-2", "column3" => "4-3", "column4" => "4-4", "column5" => "4-5"),
            array("column1" => "5-1", "column2" => "5-2", "column3" => "5-3", "column4" => "5-4", "column5" => "5-5"),
        );
    }
}