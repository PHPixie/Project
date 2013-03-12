<?php

/**
 * This class allows you to wrap fields or values that you don't want to be escaped
 * inside the query
 * @package Database
 */
class Expression_Database
{

	/**
	 * Part of query that should not be escaped
	 * @var mixed
	 * @access public
	 */
	public $value;

	/**
	 * Marks a part of query as a database specific expression,
	 * e.g. calls to SQL functions like MAX(), SUBSTR() etc.
	 * Example
	 * <code>
	 * $q->fields(DB::expr('COUNT(*)'));
	 * </code>
	 *
	 * @param mixed $value Part of query that should not be escaped
	 * @return Expression_Database
	 * @access public
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

}
