<?php

class ModelUtils{

	/**
     * insert
     * @param string $table : A name of table to insert into
     * @param string $data : An associative array
     */
    public static function insert($table, $data, $returnId = false)
    {
        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)";

        if($returnId){
            return self::getIdOnSaveQuery($sql, $data);
        }else{
            return self::runSaveOrChangeQuery($sql, $data);
        }
    }


    /**
     * update
     * @param string $table : A name of table to insert into
     * @param string $data : An associative array
     * @param int $id : the id value to be used in the WHERE query
     */
    public static function update($table, $data, $id)
    {//error_reporting(E_ALL); ini_set('display_errors', 1);
        $fieldDetails = NULL;
        foreach($data as $key=> $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $sql = "UPDATE $table SET $fieldDetails WHERE `id` = :id";
        $data['id'] = $id;

        return self::runSaveOrChangeQuery($sql, $data);
    }



    /**
     * update_byClause
     * @param string $table : A name of table to insert into
     * @param string $data : An associative array
     * @param string $where : the WHERE query part
     */
    public static function update_byClause($table, $data, $where)
    {
        $fieldDetails = NULL;
        foreach($data as $key=> $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $sql = "UPDATE $table SET $fieldDetails WHERE $where";

        return self::runSaveOrChangeQuery($sql, $data);
    }

    /**
     * runGetQuery 
     * @param string $sql : A sql query containing the bind values
     * @param string $fetchType : Indicates the PDO retrieval method : 
                                COLUMN - retrieving one field/column in the table
                                ROW - retrieving just one row of record from the specified table
                                ALL - retrieving all records satisfied by the query
     * @param array $bind_values : An associative array of table fields and their values
     */
    public static function runGetQuery($sql, array $bind_values=null, $fetchType='ALL')
    {
        global $conn;
        $result = "";
        $stmt = $conn->prepare($sql);

        if ( !is_null($bind_values) ) {
            foreach ($bind_values as $param => $val) {
                if ( is_int($val) ) {
                    $stmt->bindValue($param, $val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($param, $val);
                }
            }
        }
        $stmt->execute();

        if($fetchType == 'COLUMN')
            $result = $stmt->fetchColumn();//fetches specified column
        else if($fetchType == 'ROW')
            $result = $stmt->fetch(PDO::FETCH_ASSOC);//fetches only one row
        else if($fetchType == 'ALL')
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);//fetches one rows

        return $result;
    }

    /**
     * runGetQueryWithArray
     * @param string $sql : A sql query containing the bind values
     * @param string $fetchType : A sql query containing the bind values
     * @param array $bind_values : An associative array of table fields and their values
     */
    public static function runGetQueryWithArray($sql, array $bind_values=null) {
		global $conn;
		$stmt = $conn->prepare($sql);
		if ( !is_null($bind_values) ) {
			foreach ($bind_values as $param => $val) {
				if ( is_int($val) ) {
					$stmt->bindValue($param, $val, PDO::PARAM_INT);
				} else {
					$stmt->bindValue($param, $val);
				}
			}
			$stmt->execute();
			return $stmt;
		} else {
			$stmt->execute();
			return $stmt;
		}
	}

    /**
     * runSaveOrChangeQuery
     * @param string $sql : A name of table to insert into
     * @param array $bind_values : An associative array of table fields and their values
     */
    public static function runSaveOrChangeQuery($sql, array $bind_values=null)
    {
        global $conn;
        if ( !is_null($bind_values) ) {
            try {
                $stmt = $conn->prepare($sql);
                foreach ($bind_values as $param => $val) {
                    if ( is_int($val) ) {
                        $stmt->bindValue($param, $val, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue($param, $val);
                    }
                }
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
        else {
            try {
                $conn->query($sql);
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
    }

    /**
     * getIdOnSaveQuery
     * @param string $sql : A name of table to insert into
     * @param array $bind_values : An associative array of table fields and their values
     */
    public static function getIdOnSaveQuery($sql, array $bind_values=null)
    {
        global $conn;
        if ( !is_null($bind_values) ) {
            try {
                $stmt = $conn->prepare($sql);
                foreach ($bind_values as $param => $val) {
                    if ( is_int($val) ) {
                        $stmt->bindValue($param, $val, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue($param, $val);
                    }
                }
                $stmt->execute();
                return $conn->lastInsertId();
            } catch (PDOException $e) {
                return false;
            }
        }
        else {
            try {
                $conn->query($sql);
                return $conn->lastInsertId();
            } catch (PDOException $e) {
                return false;
            }
        }
    }

    /**
     * getCol
     * @param string $table : A name of table to query for record
     * @param string $col : A column on the table to return
     * @param string $where_field : A column to use in WHERE clause
     * @param string $field_value : A value for the column in the WHERE clause
     */
    public static function getCol($table, $col, $where_field, $field_value)
    {
        global $conn;
        $sql = "SELECT `{$col}` FROM `{$table}` WHERE `{$where_field}` = :field_value";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':field_value', $field_value);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }

    /**
     * getCol
     * @param string $table : A name of table to query for record
     * @param string $col : A column on the table to return
     * @param array $arrayFilter : An associative array of columns and their values to use in the WHERE clause
     */
    public static function getCol_usingArrayFilters($table, $col, $arrayFilter)
	{
		$where_clause = "";
		$cnt = 1;

		//generate where clause
		if($arrayFilter && is_array($arrayFilter)){
			foreach ($arrayFilter as $key => $value) {
				$where_clause .= "`{$key}` = :{$key}";
				$where_clause .= ($cnt < count($arrayFilter))? " AND " : "";
				$cnt++;
			}
		}
		$sql = "SELECT `{$col}` FROM `{$table}` WHERE {$where_clause}";
		$stmt = self::runGetQueryWithArray($sql, $arrayFilter);
	    $result = $stmt->fetchColumn();
	    return $result;
	}
	
}