<?php

/**
 * Allows iterating over ORM objects inside loops lie 'foreach',
 * while preserving performance by working with only a single row
 * at a time. It wraps conveniently wraps around Database_Result class
 * returning ORM object instead of just data object.
 *
 * @see Database_Result
 * @package ORM
 */
class Result_ORM implements Iterator
{

	/**
	 * Name of the model that the rows belong to
	 * @var string
	 * @access private
	 */
	private $_model;

	/**
	 * Database result
	 * @var Result_Database
	 * @access private
	 */
	private $_dbresult;

	/**
	 * Rules for preloaded relationships
	 * @var array
	 * @access private
	 */
	private $_with = array();

	/**
	 * Initialized an Result_ORM with which model to use and which result to
	 * iterate over
	 *
	 * @param string          $model  Model name
	 * @param Result_Database $dbresult Database result
	 * @param array           $with Array of rules for preloaded relationships
	 * @return void
	 * @access public
	 */
	public function __construct($model, $dbresult, $with = array())
	{
		$this->_model = $model;
		$this->_dbresult = $dbresult;
		foreach ($with as $path => $rel)
		{
			$this->_with[] = array(
				'path' => explode('.', $path),
				'path_count' => count(explode('.', $path)),
				'model' => $rel['model'],
				'columns' => $rel['model']->columns(),
			);
		}
	}

	/**
	 * Rewinds database cursor to the first row
	 *
	 * @return void
	 * @access public
	 */
	function rewind()
	{
		$this->_dbresult->rewind();
	}

	/**
	 * Gets an ORM Model of the current row
	 *
	 * @return ORM Model of the current row of the result set
	 * @access public
	 */
	function current()
	{
		$model = new $this->_model;

		if (!$this->_dbresult->valid())
		{
			return $model;
		}

		if (empty($this->_with))
		{
			return $model->values((array) $this->_dbresult->current(), true);
		}

		$data = (array) $this->_dbresult->current();

		$model_data = array();
		foreach ($model->columns() as $column)
		{
			$model_data[$column] = array_shift($data);
		}
		$model->values($model_data, true);

		foreach ($this->_with as $rel)
		{
			$rel_data = array();
			foreach ($rel['columns'] as $column)
			{
				$rel_data[$column] = array_shift($data);
			}
			$rel['model']->values($rel_data, true);

			$owner = $model;
			foreach ($rel['path'] as $key => $child)
			{
				if ($key == $rel['path_count'] - 1)
				{
					$owner->cached[$child] = $rel['model'];
				}
				else
				{
					$owner = $owner->cached[$child];
				}
			}
		}

		return $model;
	}

	/**
	 * Gets current rows' index number
	 *
	 * @return int Row number
	 * @access public
	 */
	function key()
	{
		return $this->_dbresult->key();
	}

	/**
	 * Iterates to the next row in the result
	 *
	 * @return void
	 * @access public
	 */
	function next()
	{
		$this->_dbresult->next();
	}

	/**
	 * Checks if current row is valid.
	 *
	 * @return bool returns false if we reach the end of the result set.
	 * @access public
	 */
	function valid()
	{
		return $this->_dbresult->valid();
	}

	/**
	 * Returns an array of all rows as ORM objects if $rows is False,
	 * or just an array of result rows with each row being a standard object,
	 * this can be useful for functions like json_encode.
	 *
	 * @param boolean $rows Whether to return just rows and not ORM objects
	 * @return array   Array of ORM objects or standard objects representing rows
	 * @access public
	 */
	public function as_array($rows = false)
	{
		if (!$rows)
		{
			$arr = array();
			foreach ($this as $row)
				$arr[] = $row;
			return $arr;
		}

		if (empty($this->_with))
		{
			return $this->_dbresult->as_array();
		}

		$arr = array();
		$model = new $this->_model;
		foreach ($this->_dbresult as $data)
		{
			$row = new stdClass;
			$data = (array) $data;
			foreach ($model->columns() as $column)
			{
				$row->$column = array_shift($data);
			}

			foreach ($this->_with as $rel)
			{
				$rel_data = new StdClass;
				foreach ($rel['columns'] as $column)
				{
					$rel_data->$column = array_shift($data);
				}

				$owner = &$row;
				foreach ($rel['path'] as $key => $child)
				{
					if ($key == $rel['path_count'] - 1)
					{
						$owner->$child = $rel_data;
					}
					else
					{
						$owner = &$owner->$child;
					}
				}
			}
			$arr[] = $row;
		}

		return $arr;
	}

}