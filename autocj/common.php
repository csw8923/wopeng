<?php
/**
 * action: sysfun 系统操作调用类
 * User: chensw
 * Date: 12-10-10
 * Time: 15:34
 */
   class sysfun{
	function __construct($dbname)
	{
		 require("config.php");
		 @ $db = new mysqli($host,$user,$pass,$dbdata);
		 if (mysqli_connect_errno()) {
			 echo 'Error: 不能链接数据库,请重新配置数据库信息在尝试.';
			 exit;
		 }
		 $this->db = $db;
		 $this->dbname = $dbname;
	}

	/**
	 * 对特殊字符进行过滤
	 *
	 * @param value  值
	 */
	public function escape($value) {
		if(is_null($value))return 'NULL';
		if(is_bool($value))return $value ? 1 : 0;
		if(is_int($value))return (int)$value;
		if(is_float($value))return (float)$value;
		if(@get_magic_quotes_gpc())$value = stripslashes($value);
		$search=array("\\","\0","\n","\r","\x1a","'",'"');
        $replace=array("\\\\","[NULL]","\\n","\\r","\Z","''",'\"');
        return '\''.str_replace($search,$replace,$value).'\'';
	}
	
	/**
	 * 从数据表中查找记录
	 *
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param sort    排序，等同于"ORDER BY "
	 * @param fields    返回的字段范围，默认为返回全部字段的值
	 * @param limit    返回的结果数量限制，等同于"LIMIT "，如$limit = " 3, 5"，即是从第3条记录（从0开始计算）开始获取，共获取5条记录
	 *                 如果limit值只有一个数字，则是指代从0条记录开始。
	 */
	public function findAll($conditions = null, $sort = null, $fields = null)
	{
		 // 数组条件的处理
		 $where = "";
		 $fields = empty($fields) ? "*" : $fields;
		 if(is_array($conditions)){
				$join = array();
				foreach( $conditions as $key => $condition ){
					$condition = $this->escape($condition);
					$join[] = "{$key} = {$condition}";
				}
				$where = " where ".join(" AND ",$join);
		  }
		  else
		  { if(null != $conditions)$where = $conditions;}

		  if(null != $sort){
			$sort = "ORDER BY {$sort}";
		  }else{
			$sort = "ORDER BY id ASC";
		  }
		  $query = "select {$fields} from {$this->dbname} {$where} {$sort}";
		  $this->db->query("set names utf8;"); // 编码
		  $result = $this->db->query($query);
		  $num_results = $result->num_rows;  // num_results 数据库数量
		  
		  // 整理并输出数据
		  $i=0;
		  $data_temp=array();
		  while($i < $num_results)
		  {
			  $row = $result->fetch_assoc();
			  $data_temp[] = $row;
			  $i++;
		  }
		  
		  // 关闭数据库
		  // $result->free();
		  // $this->db->close();
		  return $data_temp;
	}
	
		/**
	 * 计算符合条件的记录数量
	 *
	 * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，
	 * 请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 */
	public function findCount($conditions = null)
	{
		$where = "";
		$fields = empty($fields) ? "*" : $fields;
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = $conditions;
		}
		$query = "select COUNT({$fields}) AS SP_COUNTER FROM {$this->dbname} {$where}";
		$this->db->query("set names utf8;"); // 编码
		$result = $this->db->query($query);
		$num_results = $result->num_rows;  // num_results 数据库数量

		// 关闭数据库
		// $result->free();
		// $this->db->close();
		return $result[0]['SP_COUNTER'];
	}

	/**
	 * 在数据表中新增一行数据
	 *
	 * @param row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
	 */
	public function create($row)
	{
		if(!is_array($row))return FALSE;
		if(empty($row))return FALSE;
		foreach($row as $key => $value){
			$cols[] = $key;
			$vals[] = $this->escape($value);
		}
		$col = join(',', $cols);
		$val = join(',', $vals);
		$query = "INSERT INTO {$this->dbname} ({$col}) VALUES ({$val})";
		$this->db->query("set names utf8;"); // 编码
		return $this->db->query($query);
	}
	
	/**
	 * 按条件删除记录
	 *
	 * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 */
	public function delete($conditions)
	{
		$where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ( ".join(" AND ",$join). ")";
		}else{
			if(null != $conditions)$where = $conditions;
		}
		$query = "DELETE FROM {$this->dbname} {$where}";
		$this->db->query("set names utf8;"); // 编码
		return $this->db->query($query);
	}
	
	 /**
	 * 修改数据，该函数将根据参数中设置的条件而更新表中数据
	 * 
	 * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param row    数组形式，修改的数据，
	 *  此参数的格式用法与create的$row是相同的。在符合条件的记录中，将对$row设置的字段的数据进行修改。
	 */
	public function update($conditions, $row)
	{
		$where = "";
		if(empty($row))return FALSE;
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = $conditions;
		}
		foreach($row as $key => $value){
			$value = $this->escape($value);
			$vals[] = "{$key} = {$value}";
		}
		$values = join(", ",$vals);
		$query = "UPDATE {$this->dbname} SET {$values} {$where}";
		$this->db->query("set names utf8;"); // 编码
		return $this->db->query($query);
	}
	
   }
?>