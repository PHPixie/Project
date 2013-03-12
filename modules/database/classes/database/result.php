<?php

/**
 * Allows to access database results in a unified way and
 * provides iterator support, so it can be used inside loops like 'foreach'
 * @package Database
 */
abstract class Result_Database implements Iterator
{

	/**
	 * Current row number
	 * @var integer
	 * @access protected
	 */
	protected $_position = -1;

	/**
	 * Database result object
	 * @var mixed
	 * @access protected
	 */
	protected $_result;

	/**
	 * Current row
	 * @var object
	 * @access protected
	 */
	protected $_row;

	/**
	 * If at least one row has been fetched
	 * @var object
	 * @access protected
	 */
	protected $_fetched = false;

	/**
	 * Returns current row
	 *
	 * @return object Current row in result set
	 * @access public
	 */
	public function current()
	{
		$this->check_fetched();
		return $this->_row;
	}

	/**
	 * Gets the number of the current row
	 *
	 * @return integer Row number
	 * @access public
	 */
	public function key()
	{
		$this->check_fetched();
		return $this->_position;
	}

	/**
	 * Check if current row exists.
	 *
	 * @return bool True if row exists
	 * @access public
	 */
	public function valid()
	{
		$this->check_fetched();
		return $this->_row != null;
	}

	/**
	 * Returns all rows as array
	 *
	 * @return array  Array of rows
	 * @access public
	 */
	public function as_array()
	{
		$arr = array();
		foreach ($this as $row)
		{
			$arr[] = $row;
		}
		return $arr;
	}

	/**
	 * Checks if the rows from the result set have
	 * been fetched at least once. If not fetches first row.
	 *
	 * @access public
	 */
	protected function check_fetched()
	{
		if (!$this->_fetched)
		{
			$this->_fetched = true;
			$this->next();
		}
	}

	/**
	 * Gets a column from the current row in the set
	 *
	 * @param  string $column Column name
	 * @return mixed  Column value
	 * @access public
	 */
	public function get($column)
	{
		if ($this->valid() && isset($this->_row->$column))
		{
			return $this->_row->$column;
		}
	}

}