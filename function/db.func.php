<?php
define('DB_AUTO_INCREMENT', '123ff$%^&*()_');
class DB{
	private static $connection;
	private static $db;
	public $resource = false;
	public $current_collection = '';
	public $old_resource = '';
	private $finding = false;
	private $finding_fields = false;
	private $skip = 0;
	private $limit = 0;
	private $sort = array();
	private $sort_condition = array();
	private $keymap;
	private $each_callback;
	public $sorting;
	public $sorting_seq = 0;
	private static $binding = array("member"=>
		array("tel"=>
			array(
				"product",
				"transaction",
			)
		)
	);
	private static $reversed = array();
	public function __construct($collection){
		$this->current_collection  = $collection;
		$this->resource = self::$connection->selectCollection(self::$db, $collection);
	}
	public function sorter($a, $b) {
		$join_field = key($this->sort);
		$org_field = current($this->sort);
		if(!isset($a[$join_field]) || !isset($b[$join_field]))
			return (isset($a[$join_field]) & !isset($b[$join_field])) ? 1 : -1;
		$avalue = $a[$join_field];
		$bvalue = $b[$join_field];
		//echo $avalue.'_'.$bvalue.'<br>';
		$field_sort = false;
		
		if(is_array($org_field)){
			$field_sort = true;
			$avalue = array_search($avalue, $org_field);
			$bvalue = array_search($bvalue, $org_field);
		}
		if ($avalue == $bvalue) {
			$k = next($this->sort);
			if($k==''){
				return (reset($this->sort) && false);
			}else
				return $this->sorter($a, $b);
		}
		$result = ($avalue < $bvalue) ? -1 : 1;
		return $field_sort ? $org_field : $result*$org_field;
	}
	public static function init($db){
		self::$connection = new Mongo();
		self::$db = $db;
		return self::$connection;
	}
	public static function create($collection){
		return new DB($collection);
	}
	public static function getMongo(){
		return self::$connection;
	}
	public function flush(){
		$this->resource = $this->get(null, $this->resource);
		return $this;
	}
	public static function setResource($resource){
		$dba = DB::create('testest');
		$dba->resource = $resource;
		return $dba;
	}
	public function get($single_result=-1, $spec=null){
		$resource = $spec ? $spec : $this->resource;
		$resource = is_array($resource) ? $resource : $this->iterator_to_array($resource);
		$skip = $this->skip;
		$limit = $this->limit;
		$sort = $this->sort;
		$keymap = $this->keymap;
		if($skip>0 || $limit>0 || $sort || $keymap){
			reset($this->sort);
			$sorting_seq = 0;
			if($sort)
				uasort($resource, array($this, 'sorter') );
			$limit = $limit ? $limit : null;
			$resource = array_slice($resource, $skip, $limit);
			if($keymap){
				/*foreach($resource as $kk=>$each){
					foreach($keymap as $org=>$new){
						$val = $each[$org];
						unset($each[$org]);
						$each[$new] = $val;
					}
					$resource[$kk]=$each;
				}
				$this->keymap = null;*/
			}
			$this->reset_order();
		}
		if($single_result===true)
			return current($resource);
		return $resource;
	}
	private function iterator_to_array($resource){
		return iterator_to_array($resource);
	}
	public function join($collection2){
		$this->old_resource = $this->get(null, $this->resource);
		$this->resource = self::$connection->selectCollection(self::$db, $collection2);
		return $this;
	}
	public function get_key($key){
		if(!is_object($key))
		return $key;
		
		return serialize($key);
	}
	public function on($condition){
		$k = array_keys($condition);
		$v = array_values($condition);
		$key = $k[0];
		$value = $v[0];
		$org_array = $this->old_resource;
		$ins = array();
		foreach($org_array as $_id=>$item)
			$ins[] = $item[$value];
		$condit = array($key => array('$in'=>$ins) );
		$this->finding = !is_array($this->finding) ? array() : $this->finding;
		$condit = array_merge($condit, $this->finding);
		$new_array = $this->get( null, $this->resource->find( $condit ) );
		$orgs = array();
		foreach($new_array as $nitem){
			$items[$this->get_key($nitem[$key])] = $nitem;
			unset($items[$this->get_key($nitem[$key])]['_id']);
		}
		foreach($org_array as $k=>$oitem){
			$orgs[$k] = isset($items[$this->get_key($oitem[$value])]) ? array_merge($oitem, $items[ $this->get_key($oitem[$value]) ]) : $oitem;
		}
		
		$this->resource = $orgs;
		$this->old_resource = null;
		return $this;
	}
	public function sort($array=null){
		$resource = $this->resource;
		if(is_array($resource))
			$this->sort = $array;
		else{
			$this->resource = $array!==null ? $resource->sort($array) : $resource->sort();
			$this->reset_order();
		}
		return $this;
	}
	public function shuffle(){
		$this->resource = shuffle($this->resource);
		return $this;
	}
	private function reset_order(){
		$this->skip = $this->limit = 0;
		$this->sort= array();
		$this->finding = false;
		$this->each_callback = null;
		return $this;
	}
	public function skip($args=0){
		if(is_array($this->resource)){
			$this->skip = $args;
			return $this;
		}
		$this->reset_order();
		return $this->org_skip($args);
	}
	public function limit($args=0){
		if(is_array($this->resource)){
			$this->limit = $args;
			return $this;
		}
		$this->reset_order();
		return $this->org_limit($args);
	}
	public function find($args=array(), $fields=null){
		if($fields){
			$fkeys = array_keys($fields);
			if(is_int(key($fkeys))===false)
			$fields = $fkeys;
			
			//print_array($fields);
			$this->keymap = $fields;
		}
		if(!$this->finding && is_array($this->old_resource) && !is_array($this->resource)){
			$this->finding = $args;
			$this->finding_fields = $fields;
			return $this;
		}elseif($args && is_array($this->resource)){
			$packaged = array();
			foreach($this->resource as $key=>$item){
				foreach($args as $f=>$v){
					if($item[$f]!=$v)
					continue 2;
				}
				if($fields){
					$item2 = array();
					foreach($fields as $vv)
						$item2[$vv] = $item[$vv];
					$item = $item2;
					unset($item2);
				}
				$packaged[$key] = $item;
			}
			$this->resource = $packaged;
			return $this;
		}
		return $this->org_find($args, $fields);
	}
	public function count($args=null){
		$count = $args ? $this->resource->count($args) : $this->resource->count();
		return $count;
	}
	public function getResource(){
		return is_array($this->resource) ? $this->resource : $this->resource;
	}
	public function insert($narray){
		return $this->resource->insert($narray);
	}
	public function update($find, $updates, $flags=null){
		$this->resource = $flags ? $this->resource->update($find, $updates, $flags) : $this->resource->update($find, $updates);
		return $this;
	}
	public function save($data){
		$this->resource = $this->resource->save($data);
		return $this;
	}
	public function remove($find){
		$this->resource = $this->resource->remove($find);
		return $this;
	}
	public static function mongodb(){
		$m = new Mongo();
		return $m->selectDB(self::$db);
	}
	public static function lastError(){
		return self::mongodb()->lastError();
	}
	public function insertWith($fieldname, $toInsert){
		$con = DB::increment($this->current_collection, $fieldname, 2);
		$condition = $con[1];
		$this->update($condition, array('$set'=>$toInsert) );
		return $con[0];
	}
	public static function nextIncrement($collection){
		$indexer = DB::create('increments')->find(array('collection'=>$collection))->limit(1)->get(true);
		return ++$indexer['index'];
	}
	public static function increment($collection, $fieldname, $return_type=0){
		$indexer = DB::create('increments')->find(array('collection'=>$collection))->limit(1)->get(true);
		$update = true;
		if(!$indexer){
			$indexer = array('collection'=>$collection, 'index'=>1);
			$update = false;
		}
		$index = $indexer['index'];
		//$index++;
		$inserted = false;
		while(!$inserted){
			$insert_keys=array();
			if(is_array($fieldname)){
				$keys = array();
				foreach($fieldname as $names){
					$keys[$names] = 1;
					$insert_keys[$names] = $index;
				}
			}else{
				$keys = array($fieldname=>1);
				$insert_keys = array($fieldname=>$index);
			}
			DB::create($collection)->ensureIndex($keys, array('unique'=>1));
			DB::create($collection)->insert($insert_keys);
			$id = $insert_keys['_id'];
			$inserted = DB::create($collection)->find(array('_id'=>$id))->limit(1)->get(true);
			$index++;
		}
		//$index--;
		$return_condition = array('_id'=>$id);
		$indexer['index'] = $index;
		$increments = DB::create('increments');
		if($update)
			$increments->update(array('collection'=>$collection), array('$set'=>array('index'=>$index)) );
		else
			$increments->insert($indexer);
		$index--;
		if($return_type==0)
		return $index;
		elseif($return_type==1)
		return $return_condition;
		else
		return array($index, $return_condition);
	}
	public static function date($time){
		return new MongoDate($time);
	}
	public function ensureIndex($key, $options=null){
		$this->resource->ensureIndex($key, $options);
		return $this;
	}
	public function regex($reg){
		return new MongoRegex($reg);
	}
	public function org_find($args=array(), $fields=null){
		if(!$fields){
			$this->resource = !$args ? $this->resource->find() : $this->resource->find($args);
		}else{
		$args=$args==null?array():$args;
		$this->resource = $this->resource->find($args, $fields);}
		return $this;
	}
	public function org_limit($args=null){
		$this->resource = isset($args) ? $this->resource->limit($args) : $this->resource->limit();
		return $this;
	}
	public function org_skip($args=null){
		$this->resource = isset($args) ? $this->resource->skip($args) : $this->resource->skip();
		return $this;
	}
	public function each($callback){
		if(is_array($this->resource)){
			//$this->resource
		}
		return $this;
	}
	public function __toString(){
		return 'DB Resource';
	}
	private function reverse_bind(){
		$bind = self::$binding;
	}
	public static function convert($array){
		return self::find_date($array);
	}
	public function group(){
		$args = func_get_args();
		$g = call_user_func_array(array($this->resource, 'group'), $args);
		return $g;
	}
	public static function find_date($arr, $key=''){
		if(is_array($arr)){
			$depth++;
			$keys = array_keys($arr);
			if(count($arr)==1 && $keys[0]==='$date'){
				//echo $keys[0].' is $date!<br>';
				return new MongoDate($arr['$date']);
			}
			foreach($arr as $k=>$v){
				$v = self::find_date($v, $k);
				$arr[$k] = $v;
			}
			$depth--;
			return $arr;
		}else{
			return $arr;
		}
	}
	public function drop(){
		return $this->resource->drop();
	}
}
$_MDB = function($collection){
	return DB::create($collection);
};