<?php

namespace App\Service;

use PDO;
use PDOException;


class sspService
{

    static function data_output($columns, $data)
    {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                if (isset($column['formatter'])) {
                    if (empty($column['db'])) {
                        $row[$column['dt']] = $column['formatter']($data[$i]);
                    } else {
                        $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                    }
                } else {
                    if (!empty($column['db'])) {
                        $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                    } else {
                        $row[$column['dt']] = "";
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }


    static function db($conn)
    {
        if (is_array($conn)) {
            return self::sql_connect($conn);
        }

        return $conn;
    }

    static function limit($request, $columns)
    {
        $limit = '';
        $a = $request['start'];
        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }

        return $limit;
    }

    static function order($request, $columns)
    {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = '`' . $column['db'] . '` ' . $dir;
                }
            }

            if (count($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }

        return $order;
    }

    static function filter($request, $columns, &$bindings)
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];

            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['searchable'] == 'true') {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        $globalSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
                    }
                }
            }
        }

        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                $str = $requestColumn['search']['value'];

                if (
                    $requestColumn['searchable'] == 'true' &&
                    $str != ''
                ) {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        $columnSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
                    }
                }
            }
        }

        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

    static function simple($request, $conn, $table, $primaryKey, $columns)
    {
        $bindings = array();
        $db = self::db($conn);
        // $db = $this->db;

        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);
        $select =implode("`, `", self::pluck($columns, 'db')) ;
        $data = self::sql_exec(
            $db,
            $bindings,
            "SELECT `$select`
			 FROM `$table`
			 $where
			 $order
			 $limit"
        );

        $resFilterLength = self::sql_exec(
            $db,
            $bindings,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`
			 $where"
        );
        $recordsFiltered = $resFilterLength[0][0];

        $resTotalLength = self::sql_exec(
            $db,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`"
        );
        $recordsTotal = $resTotalLength[0][0];

        return array(
            "draw"            => isset($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => self::data_output($columns, $data)
        );
    }

    static function SelectGroupBy($request, $conn, $table, $primaryKey, $columns,  $joinQuery, $select, $filter, $groupBy, $top)
    {
        $bindings = array();
        $db = self::db($conn);

        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        // $where = self::filter($request, $columns, $bindings);
        if ($top == 'true')
            $query =  "SELECT $select FROM $joinQuery WHERE $filter GROUP BY $groupBy HAVING average_score IS NOT NULL  ORDER BY average_score DESC LIMIT 3";
        else
            $query =  "SELECT $select FROM $joinQuery WHERE $filter GROUP BY $groupBy $order $limit";
        $data = self::sql_exec(
            $db,
            $bindings,
            $query
        );
        $resFilterLength = self::sql_exec(
            $db,
            $bindings,
            "SELECT COUNT(*) FROM $joinQuery "
        );
        $recordsFiltered = $resFilterLength[0][0];

        $resTotalLength = self::sql_exec(
            $db,
            "SELECT COUNT(*) FROM $joinQuery "
        );
        $recordsTotal = $resTotalLength[0][0];

        return array(
            "draw"            => isset($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => self::data_output($columns, $data)
        );
    }
    static function SelectJoin($request, $conn, $table, $primaryKey, $columns,  $joinQuery, $select, $filter)
    {
        $bindings = array();
        $db = self::db($conn);

        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);
        if ($filter == null) {
            $query =  "SELECT $select
            FROM $joinQuery 
            $order
            $limit
            ";
        } else {
            $query =  "SELECT $select
            FROM $joinQuery WHERE $filter  
            $order
            $limit
            ";
        }

        $data = self::sql_exec(
            $db,
            $bindings,
            $query
        );
        if ($filter == null) {
            $resFilterLength = self::sql_exec(
                $db,
                $bindings,
                "SELECT COUNT(*) FROM $joinQuery "
            );
            $recordsFiltered = $resFilterLength[0][0];

            $resTotalLength = self::sql_exec(
                $db,
                "SELECT COUNT(*) FROM $joinQuery "
            );
            $recordsTotal = $resTotalLength[0][0];
        } else {
            $resFilterLength = self::sql_exec(
                $db,
                $bindings,
                "SELECT COUNT(*) FROM $joinQuery WHERE $filter"
            );
            $recordsFiltered = $resFilterLength[0][0];

            $resTotalLength = self::sql_exec(
                $db,
                "SELECT COUNT(*) FROM $joinQuery WHERE $filter"
            );
            $recordsTotal = $resTotalLength[0][0];
        }


        return array(
            "draw"            => isset($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => self::data_output($columns, $data)
        );
    }


    static function complex($request, $conn, $table, $primaryKey, $columns, $whereResult = null, $whereAll = null)
    {
        $bindings = array();
        $db = self::db($conn);
        $localWhereResult = array();
        $localWhereAll = array();
        $whereAllSql = '';

        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        $whereResult = self::_flatten($whereResult);
        $whereAll = self::_flatten($whereAll);

        if ($whereResult) {
            $where = $where ?
                $where . ' AND ' . $whereResult :
                'WHERE ' . $whereResult;
        }

        if ($whereAll) {
            $where = $where ?
                $where . ' AND ' . $whereAll :
                'WHERE ' . $whereAll;

            $whereAllSql = 'WHERE ' . $whereAll;
        }
        $select =implode("`, `", self::pluck($columns, 'db')) ;
        $data = self::sql_exec(
            $db,
            $bindings,
            "SELECT `$select`
			 FROM `$table`
			 $where
			 $order
			 $limit"
        );

        $resFilterLength = self::sql_exec(
            $db,
            $bindings,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`
			 $where"
        );
        $recordsFiltered = $resFilterLength[0][0];

        $resTotalLength = self::sql_exec(
            $db,
            $bindings,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` " .
                $whereAllSql
        );
        $recordsTotal = $resTotalLength[0][0];

        return array(
            "draw"            => isset($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => self::data_output($columns, $data)
        );
    }


    static function sql_connect($sql_details)
    {
        try {

            $servername = $sql_details['host'];
            $username = $sql_details['username'];
            $password = $sql_details['password'];
            $dbname = $sql_details['database'];
            $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. " .
                    "The error reported by the server was: " . $e->getMessage()
            );
        }

        return $db;
    }

    static function sql_exec($db, $bindings, $sql = null)
    {
        if ($sql === null) {
            $sql = $bindings;
        }

        $stmt = $db->prepare($sql);

        if (is_array($bindings)) {
            for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
                $binding = $bindings[$i];
                $stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
            }
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }

        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }


    static function fatal($msg)
    {
        echo json_encode(array(
            "error" => $msg
        ));

        exit(0);
    }

    static function bind(&$a, $val, $type)
    {
        $key = ':binding_' . count($a);

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );

        return $key;
    }


    static function pluck($a, $prop)
    {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            if (empty($a[$i][$prop])) {
                continue;
            }
            $out[$i] = $a[$i][$prop];
        }

        return $out;
    }

    static function _flatten($a, $join = ' AND ')
    {
        if (!$a) {
            return '';
        } else if ($a && is_array($a)) {
            return implode($join, $a);
        }
        return $a;
    }
}
