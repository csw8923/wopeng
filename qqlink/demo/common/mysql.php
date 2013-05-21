<?php
class mysql
{	
	private $dbhost;//数据库地址
	private $dbuser;//数据库用户
	private $dbpass;//数据库密码
	private $dbname;//数据库名称
	private $rs;//数据库结果集
	
	function mysql($dbhost="", $dbuser="", $dbpass="",$dbname="",$db_lang="UTF-8")
	/**
	 * 构造函数，完成数据库连接
	 */
	{
		$this->dbhost = $dbhost ? $dbhost : "localhost";
		$this->dbuser = $dbuser ? $dbuser : "root";
		$this->dbpass = $dbpass ? $dbpass : "";
		$this->dbname = $dbname ? $dbname : "";
		$this->connect();
		$this->query("SET NAMES '".$db_lang."'");
		$this->select_db();
	}

	function connect($dbhost="",$dbuser="",$dbpass="") 
	/**
	 * 数据库连接
	 */
	{	
		$dbhost=$dbhost?$dbhost:$this->dbhost;
		$dbuser=$dbuser?$dbuser:$this->dbuser;
		$dbpass=$dbpass?$dbpass:$this->dbpass;
		if(!$link=@mysql_connect($dbhost, $dbuser, $dbpass)) 
		{
			$this->halt("<b style=\"color:#FF0000; font-size:12px;\">Connect To DB Server Fail.".$this->errno()."</b>");
		}
		unset($this->dbhost);
		unset($this->dbuser);
		unset($this->dbpass);
		return $link;
	}
	
	function select_db($dbname="")
	/**
	 * 选择数据库
	 */
	{	
		$dbname=$dbname?$dbname:$this->dbname;
		return mysql_select_db($dbname) or $this->halt("<b style=\"color:#FF0000; font-size:12px;\">数据库不存在</b>");
	}
	
	function query($sql)
	/**
	 * 查询数据库
	 */
	{		
		$query = mysql_query($sql);
		$this->rs=$query;		
		if(!$query) $this->halt("<b style=\"color:#FF0000;font-size:12px;\">".$sql."查询出错!</b>");
		return $query;
	}
	
	function fetch_object($rs="")
	/**
	 * 数据库查询结果转化成对象
	 */
	{	
		$rs=$rs?$rs:$this->rs;
		return @mysql_fetch_object($rs);
	}
	
	function fetch_array($rs="",$result_type = MYSQL_ASSOC)
	/**
	 * 数据库查询结果转化成数组
	 */
	{	
		$rs=$rs?$rs:$this->rs;
		return @mysql_fetch_array($rs, $result_type);
	}
	
	function fetch_row($rs="")
	/**
	 * 数据库查询结果转化成数组
	 */
	{
		$rs=$rs?$rs:$this->rs;
		return @mysql_fetch_row($rs);
	}
	
	function num_rows($rs="")
	/**
	 * 数据库查询结果条数
	 */
	{	
		$rs=$rs?$rs:$this->rs;
		return  mysql_num_rows($rs);		
	}
	
	function insert_id()
	/**
	 * 数据库执行插入时返回的ID
	 */
	{	
		return  mysql_insert_id();
	}
	
	function errno()
	/**
	 * 返回数据库的错误ID
	 */
	{
		return mysql_errno();
	}
	
	function error()
	/**
	 * 返回数据库的错误
	 */
	{
		return mysql_error();
	}

	function affected_rows()
	/**
	 * 返回数据库受影响的行数
	 */
	{
		return @mysql_affected_rows();
	}

	function halt($message ="")
	/**
	 * 数据库返回异样信息
	 */
	{	
		echo $message;
		exit();
	}
	
	function free_result($rs)
	/**
	 * 数据库摧毁结果集
	 */
	{
		mysql_free_result($rs);
	}

	function close($link)
	/**
	 * 关闭数据库
	 */
	{
		return mysql_close($link);
	}
}
?>