<?php
/**
 * 
 * @author 王涛
 * pdo封装类
 *
 */
class PdoModel 
{
	static public $pdo;
	static private $_config;
	static public function getPDO() 
	{
		if (self::$_config == NULL) 
		{
			$config = include_once 'config.php';
			$config = $config['mysql'];
			self::$_config = ((empty($config) ? array() : $config));
		}
		if (self::$pdo == NULL) 
		{
			try 
			{
				self::$pdo = new PDO('mysql:host=' . self::$_config['host'] . ';dbname=' . self::$_config['database'] . ';port=' . self::$_config['port'] . ';charset=' . self::$_config['charset'], self::$_config['username'], self::$_config['password']);
				self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				if (!(empty(self::$_config['pconnect']))) 
				{
					self::$pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
					
					exit($ex->getMessage());
				}
			}
			catch (Exception $ex) 
			{
				exit($ex->getMessage());
			}
		}
		return self::$pdo;
	}
	static public function beginTransaction() 
	{
		return self::getPDO()->beginTransaction();
	}
	static public function commit() 
	{
		return self::getPDO()->commit();
	}
	static public function rollBack() 
	{
		return self::getPDO()->rollBack();
	}
	static public function errorCode() 
	{
		return self::getPDO()->errorCode();
	}
	static public function errorInfo() 
	{
		return self::getPDO()->errorInfo();
	}
	static public function lastInsertId() 
	{
		return self::getPDO()->lastInsertId();
	}
	static public function fetch($sql, $params = array(), $fetchMode = PDO::FETCH_ASSOC) 
	{
		$results = self::prepare($sql, $params);
		$results->setFetchMode($fetchMode);
		return $results->fetch();
	}
	static public function fetchColumn($sql, $params = array()) 
	{
		$results = self::prepare($sql, $params);
		return $results->fetchColumn();
	}
	static public function fetchAll($sql, $params = array(), $keyfield = '', $fetchMode = PDO::FETCH_ASSOC) 
	{
		$results = self::prepare($sql, $params);
		$results->setFetchMode($fetchMode);
		$res = $results->fetchAll();
		$ret = $res;
		if (!(empty($res)) && !(empty($keyfield))) 
		{
			$ret = array();
			foreach ($res as $val ) 
			{
				$ret[$val[$keyfield]] = $val;
			}
		}
		return $ret;
	}

	static public function insert($table, $data = array(), $replace = false) 
	{
		$insertvalue = '';
		$msql = '';
		$params =array();
		foreach ($data as $k=>$v){
			$insertvalue .= ":{$k},";
			$msql .= "$k=values($k),";
			$params[":{$k}"] = $v;
		}
		$sql = "insert into ".tablename($table)."(".implode(',', array_keys($data)).") values(".rtrim($insertvalue,',').")";
		if($replace){
			$sql .= 	" ON DUPLICATE KEY UPDATE ".rtrim($msql,',');
		}

		return self::exec($sql, $params);
	}
	static public function update($table, $data = array(), $params = array(), $glue = 'AND') 
	{
		$fields = self::implode($data, ',');
		$condition = self::implode($params, $glue);
		$params = array_merge($fields['params'], $condition['params']);
		$sql = 'UPDATE ' . tablename($table) . ' SET ' . $fields['fields'];
		$sql .= (($condition['fields'] ? ' WHERE ' . $condition['fields'] : ''));
		return self::exec($sql, $params);
	}
	static public function delete($table, $params = array(), $glue = 'AND') 
	{
		$condition = self::implode($params, $glue);
		$sql = 'DELETE FROM ' . tablename($table);
		$sql .= (($condition['fields'] ? ' WHERE ' . $condition['fields'] : ''));
		return self::exec($sql, $condition['params']);
	}
	static private function implode($params, $glue = ',') 
	{
		$result = array( 'fields' => ' 1 ', 'params' => array() );
		$split = '';
		$suffix = '';
		$allow_operator = array('>', '<', '<>', '!=', '>=', '<=', '+=', '-=', 'LIKE', 'like');
		if (in_array(strtolower($glue), array('and', 'or'))) 
		{
			$suffix = '__';
		}
		if (!(is_array($params))) 
		{
			$result['fields'] = $params;
			return $result;
		}
		if (is_array($params)) 
		{
			$result['fields'] = '';
			foreach ($params as $fields => $value ) 
			{
				$operator = '';
				if (strpos($fields, ' ') !== false) 
				{
					list($fields, $operator) = explode(' ', $fields, 2);
					if (!(in_array($operator, $allow_operator))) 
					{
						$operator = '';
					}
				}
				if (empty($operator)) 
				{
					$fields = trim($fields);
					if (is_array($value) && !(empty($value))) 
					{
						$operator = 'IN';
					}
					else 
					{
						$operator = '=';
					}
				}
				else if ($operator == '+=') 
				{
					$operator = ' = `' . $fields . '` + ';
				}
				else if ($operator == '-=') 
				{
					$operator = ' = `' . $fields . '` - ';
				}
				else 
				{
					if (($operator == '!=') || ($operator == '<>')) 
					{
						if (is_array($value) && !(empty($value))) 
						{
							$operator = 'NOT IN';
						}
					}
				}
				if (is_array($value) && !(empty($value))) 
				{
					$insql = array();
					$value = array_values($value);
					foreach ($value as $k => $v ) 
					{
						$insql[] = ':' . $suffix . $fields . '_' . $k;
						$result['params'][':' . $suffix . $fields . '_' . $k] = ((is_null($v) ? '' : $v));
					}
					$result['fields'] .= $split . '`' . $fields . '` ' . $operator . ' (' . implode(',', $insql) . ')';
					$split = ' ' . $glue . ' ';
				}
				else 
				{
					$result['fields'] .= $split . '`' . $fields . '` ' . $operator . '  :' . $suffix . $fields;
					$split = ' ' . $glue . ' ';
					$result['params'][':' . $suffix . $fields] = ((is_null($value) || is_array($value) ? '' : $value));
				}
			}
		}
		return $result;
	}
	static public function exec($sql, $params = array()) 
	{
		try{
			$results = self::prepare($sql, $params);
			if (preg_match('/^\\s*(INSERT\\s+INTO|REPLACE\\s+INTO)\\s+/i', $sql))
			{
				return (int) self::getPDO()->lastInsertId();
			}
			return $results->rowCount();
		}catch(Exception $ex){
			return 0;
		}
	}
	static protected function prepare($sql, $params = array()) 
	{
		try 
		{
			$stmt = self::getPDO()->prepare($sql);
			if (!(is_array($params))) 
			{
				$params = array();
			}
			$exec = $stmt->execute($params);
			if ($exec) 
			{
				return $stmt;
			}
			return false;
		}
		catch (Exception $ex) 
		{
			/*if ($ex->getCode() == 'HY000') 
			{
				self::$pdo = NULL;
				return self::prepare($sql, $params);
			}*/
			throw $ex;
		}
	}
}
function pdo_query($sql, $params = array()) 
{
	return PdoModel::exec($sql, $params);
}
function pdo_fetchcolumn($sql, $params = array()) 
{
	return PdoModel::fetchColumn($sql, $params);
}
function pdo_fetch($sql, $params = array()) 
{
	return PdoModel::fetch($sql, $params);
}
function pdo_fetchall($sql, $params = array(), $keyfield = '') 
{
	return PdoModel::fetchAll($sql, $params, $keyfield);
}
function pdo_update($table, $data = array(), $params = array(), $glue = 'AND') 
{
	return PdoModel::update($table, $data, $params, $glue);
}
function pdo_insert($table, $data = array(), $replace = false) 
{
	return PdoModel::insert($table, $data, $replace);
}
function pdo_delete($table, $params = array(), $glue = 'AND') 
{
	return PdoModel::delete($table, $params, $glue);
}
function pdo_insertid() 
{
	return PdoModel::lastInsertId();
}
function pdo_begin() 
{
	PdoModel::beginTransaction();
}
function pdo_commit() 
{
	PdoModel::commit();
}
function pdo_rollback() 
{
	PdoModel::rollBack();
}
?>