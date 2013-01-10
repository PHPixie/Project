<?php

/**
 * ORM allows you to access database items and their relationships in an OOP manner,
 * it is easy to setup and makes a lot of use of naming convention.
 *
 * @method mixed limit(int $limit = null) Set number of rows to return.
 *               If NULL is passed than no limit is used.
 *               Without arguments returns current limit, returns self otherwise.
 *
 * @method mixed offset(int $offset = null) Set the offset for the first row in result.
 *               If NULL is passed than no limit is used.
 *               Without arguments returns current offset, returns self otherwise.
 *
 * @method mixed orderby(string $column, string $dir) Adds a column to ordering parameters
 *
 * @method mixed where(mixed $key, mixed $operator = null, mixed $val = null) behaves just like Query_Database::where()
 *
 * @see Query_Database::where()
 * @package ORM
 */
class ORM {

    /**
     * Specifies which table the model will use, can be overridden
     * @var string 
     * @access public  
     */
	public $table = null;

    /**
     * Specifies which connection the model will use, can be overridden
	 * but a model can have relationships only with models utilizing the same connection
     * @var string 
     * @access public 
     */
	public $connection = 'default';

    /**
     * Specifies which column is treated as PRIMARY KEY
     * @var string 
     * @access public 
     */
	public $id_field='id';

    /**
     * You can define 'Belongs to' relationships buy changing this array
     * @var array     
     * @access protected 
     */
	protected $belongs_to=array();

    /**
     * You can define 'Has one' relationships buy changing this array
     * @var array     
     * @access protected 
     */
	protected $has_one = array();

    /**
     * You can define 'Has many' relationships buy changing this array
     * @var array     
     * @access protected 
     */
	protected $has_many = array();

    /**
     * An instance of the database connection
     * @var DB 
     * @access private 
     */
	private $db;

    /**
     * Current row returned by the database
     * @var array     
     * @access protected 
     */
	protected $_row = array();

    /**
     * Associated query builder
     * @var Query_Database 
     * @access public 
     */
	public $query;

    /**
     * A flag whether the row was loaded from the database
     * @var boolean 
     * @access private 
     */
	private $_loaded = false;

    /**
     * The name of the model
     * @var string 
     * @access public 
     */
	public $model_name;

    /**
     * Cached properties
     * @var array   
     * @access private 
     */
	private $_cached = array();
	
    /**
     * Constructs the model. To use ORM it is enough to 
	 * just create a model like this:
	 * <code>
	 * class Fairy_Model extends ORM { }
	 * </code>
     * By default it will assume that the name of your table
	 * is the plural form of the models' name, the PRIMARY KEY is id,
	 * and will use the 'default' connection. This behaviour is easy to be
	 * changed by overriding $table, $id and $db properties.
	 *
     * @return void   
     * @access public 
	 * @ see $table
	 * @ see $id
	 * @ see $db
     */
	public function __construct() {
		$this->query = DB::instance($this->connection)->query('select');
		$this->model_name = strtolower(get_class($this));
		if (substr($this->model_name, -6) == '_model') 
			$this->model_name=substr($this->model_name,0,-6);
		if ($this->table == null) 
			$this->table = ORM::plural($this->model_name);
		$this->query->table($this->table);
		
		foreach(array('belongs_to', 'has_one', 'has_many') as $rels) {
			$normalized=array();
			foreach($this->$rels as $key => $rel) {
				if (!is_array($rel)) {
					$key = $rel;
					$rel=array();
				}
				$normalized[$key]=$rel;
				if (!isset($rel['model'])) {
					$rel['model']=$normalized[$key]['model']=$rels=='has_many'?ORM::singular($key):$key;
				}

				$normalized[$key]['type']=$rels;
				if (!isset($rel['key']))
					$normalized[$key]['key'] = $rels != 'belongs_to'?($this->model_name.'_id'):$rel['model'].'_id';
				
				if ($rels == 'has_many' && isset($rel['through']))
					if (!isset($rel['foreign_key']))
						$normalized[$key]['foreign_key']=$rel['model'].'_id';
			}
			$this->$rels=$normalized;
			
		}
		
	}
	
    /**
     * Magic method for call Query_Database methods
     * 
     * @param string $method      Method to call
     * @param array $arguments Arguments passed to the method
     * @return mixed  Returns self if parameters were passed. If no parameters where passed returns
	 *                current value for the associated parameter
	 * @throws Exception If method doesn't exist
     * @access public  
     */
	public function __call($method, $arguments) {
		if (!in_array($method, array('limit', 'offset', 'orderby', 'where')))
			throw new Exception("Method '{$method}' doesn't exist on .".get_class($this));
		$res = call_user_func_array(array($this->query, $method), $arguments);
		if(is_subclass_of($res,'Query_Database'))
			return $this;
		return $res;
	}

    /**
     * Finds all rows that meet set criteria.
     * 
     * @return ORMResult Returns ORMResult that you can use in a 'foreach' loop.
     * @access public 
     */
	public function find_all() {
		return new ORMResult(get_class($this), $res=$this->query->execute());
	}

    /**
     * Searches for the first row that meets set criteria. If no rows match it still returns an ORM object
	 * but with its loaded() flag being False. calling save() on such an object will insert a new row.
     * 
     * @return ORM Found item or new object of the current model but with loaded() flag being False
     * @access public 
     */
	public function find() {
		$set_limit=$this->limit();
		$res = $this->limit(1)->find_all()->current();
		$this->limit($set_limit);
		return $res;
	}

    /**
     * Counts all rows that meet set criteria. Ignores limit and offset.
     * 
     * @return int Number of rows
     * @access public 
     */
	public function count_all() {
		$query = clone $this->query;
		$query->type('count');
		return $query->execute();
		
	}

    /**
     * Checks if the item is considered to be loaded from the database
     * 
     * @return boolean Returns True if the item was loaded
     * @access public  
     */
	public function loaded() {
		return $this->_loaded;
	}

    /**
     * Returns the row associated with current ORM item as an associative array
     * 
     * @return array  Associative array representing the row
     * @access public 
     */
	public function as_array() {
		return $this->_row;
	}

    /**
     * Returns a clone of  query builder that is being used to set conditions.
	 * It is useful for example if you let ORM manage building a complex query using it's relationship
	 * system, then you get the clone of that query and alter it to your liking,
	 * so there is no need to writing relationship joins yourself.
     * 
     * @return Query_Database A clone of the current query builder
     * @access public 
     */
	public function query() {
		return clone $this->query;
	}

    /**
     * You can override this method to return additional properties that you would like to use 
	 * in your model. One advantage for using this instead of just overriding __get() is that
	 * in this way the properties also get cached.
     * 
     * @param string $property The name of the property to get
     * @return void    
     * @access public  
     */
	public function get($property) {
	
	}

    /**
     * Magic method that allows accessing row columns as properties and also facilitates
	 * access to relationships and custom properties defined in get() method.
     * If a relationship is being accessed, it will return an ORM model of the related table
	 * and automatically alter its query so that all your previously set conditions will remain
	 
     * @param string   $column Name of the column, property or relationship to get
     * @return mixed   
     * @access public    
     * @throws Exception If neither property nor a relationship with such name is found
     */
	public function __get($column) {
		if (array_key_exists($column,$this->_row))
			return $this->_row[$column];
		if (array_key_exists($column,$this->_cached))
			return $this->_cached[$column];
		if (($val = $this->get($column))!==null) {
			$this->_cached[$column] = $val;
			return $val;
		}
		$relations = array_merge($this->has_one, $this->has_many, $this->belongs_to);
		
		if ($target = Misc::arr($relations, $column, false)) {
			$model = ORM::factory($target['model']);
			$model->query = clone $this->query;
			if ($this->loaded())
				$model->query->where($this->id_field,$this->_row[$this->id_field]);
			if ($target['type']=='has_many'&&isset($target['through'])) {
				$lastAlias = $model->query->lastAlias();
				$throughAlias=$model->query->addAlias();
				$newAlias = $model->query->addAlias();
				$model->query->join(array($target['through'], $throughAlias), array(
					$lastAlias.'.'.$this->id_field,
					$throughAlias.'.'.$target['key'],
				),'inner');
				$model->query->join(array($model->table, $newAlias), array(
					$throughAlias.'.'.$target['foreign_key'],
					$newAlias.'.'.$model->id_field,
				),'inner');
			}else{
				$lastAlias = $model->query->lastAlias();
				$newAlias = $model->query->addAlias();
				if ($target['type'] == 'belongs_to') {
					$model->query->join(array($model->table, $newAlias), array(
						$lastAlias.'.'.$target['key'],
						$newAlias.'.'.$model->id_field,
					),'inner');
				}else {
					$model->query->join(array($model->table, $newAlias), array(
						$lastAlias.'.'.$this->id_field,
						$newAlias.'.'.$target['key'],
					), 'inner');
				}
			}
			$model->query->fields(array("$newAlias.*"));
			if ($target['type'] != 'has_many' && $model->loaded() ) {
				$model = $model->find();
				$this->_cached[$column]=$model;
			}
			return $model;
		}

		throw new Exception("Property {$column} not found on {$this->model_name} model.");
	}

    /**
     * Magic method to update record values when set as properties or to add an ORM item to
	 * a relation. By assigning an ORM object to a relationship a relationship is created between the
	 * current item and the passed one  Using properties this way is a shortcut to the add() method.
     * 
     * @param string $column Column or relationship name
     * @param mixed $val    Column value or an ORM item to be added to a relation
     * @return void    
     * @access public  
	 * @see add()
     */
	public function __set($column, $val) {
		$relations = array_merge($this->has_one, $this->has_many, $this->belongs_to);
		if (array_key_exists($column,$relations)){
			$this->add($column, $val);
		}else{
			$this->_row[$column] = $val;
		}
		$this->_cached=array();
	}

    /**
     * Create a relationship between current item and an other one
     * 
     * @param string   $relation Name of the relationship
     * @param ORM    $model    ORM item to create a relationship with
     * @return void      
     * @access public    
     * @throws Exception Exception If relationship is not defined
     * @throws Exception Exception If current item is not in the database yet (isn't considered loaded())
     * @throws Exception Exception If passed item is not in the database yet (isn't considered loaded())
     */
	public function add($relation, $model) {
	
		if (!$this->loaded())
			throw new Exception("Model must be loaded before you try adding relationships to it. Probably you haven't saved it.");
		if (!$model->loaded())
			throw new Exception("Model must be loaded before added to a relationship. Probably you haven't saved it.");
			
		$rels = array_merge($this->has_one, $this->has_many,$this->belongs_to);
		$rel = Misc::arr($rels, $relation, false);
		if (!$rel)
			throw new Exception("Model doesn't have a '{$relation}' relation defined");
		
		if ($rel['type']=='belongs_to') {
			$key=$rel['key'];
			$this->$key = $model->_row[$this->id_field];
			$this->save();
		}elseif (isset($rel['through'])) {
			$exists = DB::instance($this->connection)->query('count')
				->table($rel['through'])
				->where(array(
					array($rel['key'],$this->_row[$this->id_field]),
					array($rel['foreign_key'],$model->_row[$model->id_field])
				))
				->execute();
			if(!$exists)
				DB::instance($this->connection)->query('insert')
					->table($rel['through'])
					->data(array(
						$rel['key'] => $this->_row[$this->id_field],
						$rel['foreign_key'] =>$model->_row[$model->id_field]
					))
					->execute();
		}else {
			$key=$rel['key'];
			$model->$key = $this->_row[$this->id_field];
			$model->save();
		}
		$this->_cached=array();
	}

    /**
     * Removes a relationship between current item and the passed one
     * 
     * @param string   $relation Name of the relationship
     * @param ORM    $model    ORM item to remove relationship with. Can be omitted for 'belongs_to' relationships
     * @return void      
     * @access public    
     * @throws Exception Exception If realtionship is not defined
     * @throws Exception Exception If current item is not in the database yet (isn't considered loaded())
     * @throws Exception Exception If passed item is not in the database yet (isn't considered loaded())
     */
	public function remove($relation, $model=false) {
		
		if (!$this->loaded())
			throw new Exception("Model must be loaded before you try removing relationships from it.");
					
		$rels = array_merge($this->has_one, $this->has_many,$this->belongs_to);
		$rel = Misc::arr($rels, $relation, false);
		if (!$rel)
			throw new Exception("Model doesn't have a '{$relation}' relation defined");
			
		if ($rel['type']!='belongs_to'&&(!$model||!$model->loaded()))
			throw new Exception("Model must be loaded before being removed from a has_one or has_many relationship.");
		if ($rel['type']=='belongs_to') {
			$key=$rel['key'];
			$this->$key = null;
			$this->save();
		}elseif (isset($rel['through'])) {
			$exists = DB::instance($this->connection)->query('delete')
				->table($rel['through'])
				->where(array(
					array($rel['key'],$this->_row[$this->id_field]),
					array($rel['foreign_key'],$model->_row[$model->id_field])
				))
				->execute();
		}else {
			$key=$rel['key'];
			$model->$key = null;
			$model->save();
		}
		$this->_cached=array();
	}

    /**
     * Deletes current item from the database
     * 
     * @return void      
     * @access public    
     * @throws Exception If the item is not in the database, e.g. is not loaded()
     */
	public function delete() {
		if (!$this->loaded())
			throw new Exception("Cannot delete an item that wasn't selected from database");
		DB::instance($this->connection)->query('delete')
			->table($this->table)
			->where($this->id_field, $this->_row[$this->id_field])
			->execute();
		$this->_cached=array();
	}

    /**
     * Deletes all items that meet set conditions. Use in the same way
	 * as you would a find_all() method.
     * 
     * @return ORM Returns self
     * @access public 
     */
	public function delete_all() {
		$query = clone $this->query;
		$query->type('delete');
		$query->execute();
		return $this;
	}

    /**
     * Saves the item back to the database. If item is loaded() it will result
	 * in an update, otherwise a new row will be inserted
     * 
     * @return ORM  Returns self
     * @access public 
     */
	public function save() {
		if (isset($this->_row[$this->id_field])) {
			$query = DB::instance($this->connection)->query('update')
				->table($this->table)
				->where($this->id_field,$this->_row[$this->id_field]);
		}else {
			$query = DB::instance($this->connection)->query('insert')
				->table($this->table);
		}
		$query->data($this->_row);
		$query->execute();
		
		if (isset($this->_row[$this->id_field])) {
			$id=$this->_row[$this->id_field];
		}else {
			$id=DB::instance($this->connection)->get_insert_id();
		}
		$row =(array) DB::instance($this->connection)->query('select')
			->table($this->table)
			->where($this->id_field, $id)->execute()->current();
		$this->values($row,true);
		return $this;
	}
	
	
    /**
     * Batch updates item columns using an associative array
     * 
     * @param array $row        Associative array of key => value pairs
     * @param boolean $set_loaded Flag to consider the ORM item loaded. Useful if you selected
	 *                            the row from the database and want to wrap it in ORM
     * @return ORM   Returns self
     * @access public  
     */
	public function values($row, $set_loaded = false) {
		$this->_row = array_merge($this->_row, $row);
		if ($set_loaded)
			$this->_loaded = true;
		$this->_cached=array();
		return $this;
	}

    /**
     * Initializes ORM model by name, and optionally fetches an item by id
     * 
     * @param string  $name Model name
     * @param mixed $id   If set ORM will try to load the item with this id from the database
     * @return ORM   ORM model, either empty or preloaded
     * @access public  
     * @static 
     */
	public static function factory($name,$id=null){
		$model = $name.'_Model'; 
		$model=new $model;
		if ($id != null)
			return $model->where($model->id_field, $id)->find()
				->data(array($model->id_field,$id));
		return $model;
	}

    /**
     * Gets plural form of a noun
     * 
     * @param string  $str Noun to get a plural form of
     * @return string  Plural form
     * @access private 
     * @static 
     */
	private static function plural($str){
		$regexes=array(
			'/^(.*?[sxz])$/i' => '\\1es',
			'/^(.*?[^aeioudgkprt]h)$/i' => '\\1es',
			'/^(.*?[^aeiou])y$/i'=>'\\1ies',
		);
		foreach($regexes as $key=>$val){
			$str = preg_replace($key, $val, $str,-1, $count);
			if ($count)
				return $str;
		}
		return $str.'s';
	}

    /**
     * Gets singular form of a noun
     * 
     * @param string $str Noun to get singular form of
     * @return string Singular form of the noun
     * @access private 
     * @static 
     */
	private static function singular($str){
		$regexes=array(
			'/^(.*?us)$/i' => '\\1',
			'/^(.*?[sxz])es$/i' => '\\1',
			'/^(.*?[^aeioudgkprt]h)es$/i' => '\\1',
			'/^(.*?[^aeiou])ies$/i' => '\\1y',
			'/^(.*?)s$/'=>'\\1'
		);
		foreach($regexes as $key=>$val){
			$str = preg_replace($key, $val, $str,-1, $count);
			if ($count)
				return $str;
		}
		return $str;
	}
}