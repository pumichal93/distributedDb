<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function checkRemoteLogs($nodes) {
    include "nodes.php";
    $config_file = file_get_contents('log.json');
    $config_data = json_decode($config_file, true);
    if (count($config_data['remote_action_log'])) {
        foreach ($config_data['remote_action_log'] as $node_host => $node_logs) {
            if (count($node_logs)) {
                $conn = new MysqliDb($node_host, $nodes[$node_host]['user'], $nodes[$node_host]['password'], $nodes[$node_host]['database_name']);
                // query log actions
                if ($conn->ping()) {
                    foreach ($node_logs as $key => $log_query) {
                        $conn->rawQuery($log_query);
                        if ($conn) {
                            unset($config_data['remote_action_log'][$node_host][$key]);
                        }
                    }
                }
            }
        }
        file_put_contents('log.json', json_encode($config_data));
    }
}

/**
 * @param MysqliDb $conn
 * @param $table
 * @param $query
 */
function logRemoteQuery($remote_host, $query) {
    // log lost connection on specific node
    $config_file = file_get_contents('log.json');
    $config_data = json_decode($config_file, true);
    $config_data['remote_action_log'][$remote_host][] = $query;
    file_put_contents('log.json', json_encode($config_data));
}

function removeRowQuery($table, $data) {
    require_once ('./vendor/thingengineer/mysqli-database-class/MysqliDb.php');
    include "config.php";
    include "nodes.php";

    checkRemoteLogs($nodes);
    // osetrit offline lokal host
    $local_host = $host;
    $local_conn = new MysqliDb($host, $user, $password, $db);
    // set where statements to remove query
    foreach ($data['where'] as $w) {
        count($w) < 3 ? $local_conn->where($w[0], $w[1]) : $local_conn->where($w[0], $w[1], $w[2]);
    }
    $local_result = $local_conn->delete($table);

    if($local_conn == -1) {
        // find if any remote node is online
        $remote_access = false;
        foreach ($nodes as $node => $conn_data) {
            $local_host = $node;
            $local_conn = new MysqliDb($node, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
            // set where statements to remove query
            foreach ($data['where'] as $w) {
                count($w) < 3 ? $local_conn->where($w[0], $w[1]) : $local_conn->where($w[0], $w[1], $w[2]);
            }
            $local_result = $local_conn->delete($table);
            if($local_result) {
                $config_file = file_get_contents('log.json');
                $config_data = json_decode($config_file, true);
                $config_data['remote_table'] = $node;
                file_put_contents('log.json', $config_data);
                $remote_access = true;
                break;
            }
        }
        if (!$remote_access) {
            echo "connection fail";
        }
    }

    if ($local_host) {
        // insert row to other rows
        foreach ($nodes as $node => $conn_data) {
            if ($node != $local_conn) {
                $db = new mysqli($node, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
                if ($db->connect_error) {
                    logRemoteQuery($node, $local_conn->getLastQuery());
                }
                else {
                    $db = new MysqliDb($node, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
                    foreach ($data['where'] as $w) {
                        count($w) < 3 ? $db->where($w[0], $w[1]) : $local_conn->where($w[0], $w[1], $w[2]);
                    }
                    $id = $db->delete($table);
                    if ($id == -1) {

                    }
                }
            }
        }
    }
}

function addNewRow($table, $data) {
    require_once ('./vendor/thingengineer/mysqli-database-class/MysqliDb.php');
    include "config.php";
    include "nodes.php";
    checkRemoteLogs($nodes);
	// osetrit offline lokal host
    $local_host = $host;
	$local_conn = new MysqliDb($host, $user, $password, $db);
	$local_result = $local_conn->insert($table, $data);
    if (!$local_result) {
        // find if any remote node is online
        $remote_access = false;
        foreach ($nodes as $node => $conn_data) {
            $local_host = $node;
            $local_conn = new MysqliDb($node, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
            $local_result = $local_conn->insert($table, $data);
            if($local_result) {
                $config_file = file_get_contents('log.json');
                $config_data = json_decode($config_file, true);
                $config_data['remote_table'] = $node;
                file_put_contents('log.json', $config_data);
                $remote_access = true;
                break;
            }
        }
        if (!$remote_access) {
            echo "connection fail";
        }
    }

    //if ($remote_access) {
        // insert row to other rows
        foreach ($nodes as $node => $conn_data) {
            if ($node != $local_conn) {
                $db = new MysqliDb($node, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
                $id = $db->insert($table, $data);
                if (!$id) {
                    logRemoteQuery($node, $local_conn->getLastQuery());
                }
            }
        }
    //}
}
?>