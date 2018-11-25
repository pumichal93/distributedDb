<?php

/**
 * Pred vykonani prikazu do DB skontroluje predosle zlzhane query na dany uzol
 *
 * @param $nodes
 * @throws Exception
 */
function checkRemoteLogs($nodes) {
    include "nodes.php";
    include "config.php";
    $config_file = file_get_contents('log.json');
    $config_data = json_decode($config_file, true);
    if (count($config_data['remote_action_log'])) {
        foreach ($config_data['remote_action_log'] as $node_host => $node_logs) {
            if (count($node_logs)) {
                $conn = isset($nodes[$node_host]) ?
                    new mysqli($node_host, $nodes[$node_host]['user'], $nodes[$node_host]['password'], $nodes[$node_host]['database_name']) :
                    new mysqli($node_host, $user, $password, $db);

                if ($conn->ping()) {
                    $conn_log = new MysqliDb($conn);
                    foreach ($node_logs as $key => $log_query) {
                        $conn_log->rawQuery($log_query);
                        if ($conn_log) {
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
 * Pri strate spojenie aspon s jednzm uzlom sa ulozi query poziadavka s danym uzlom.
 *
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
    $remote_access = false;
    $id = false;
    $local_host = $host;
    $conn = new mysqli($local_host, $user, $password, $db);
    if ($conn->ping()) {
        $local_conn = new MysqliDb($conn);
        // set where statements to remove query
        foreach ($data['where'] as $w) {
            count($w) < 3 ? $local_conn->where($w[0], $w[1]) : $local_conn->where($w[0], $w[1], $w[2]);
        }
        $id = $local_conn->delete($table);

        if ($id == 1)
            $remote_access = true;
    }

    if (!$conn->ping() || $id === -1) {
        var_dump($conn->ping());
        var_dump($id);
        die();
        // find if any remote node is online
        foreach ($nodes as $node => $conn_data) {
            $local_host = $node;
            $conn = new mysqli($node, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
            if ($conn->ping()) {
                $local_conn = new MysqliDb($conn);
                // set where statements to remove query
                foreach ($data['where'] as $w) {
                    count($w) < 3 ? $local_conn->where($w[0], $w[1]) : $local_conn->where($w[0], $w[1], $w[2]);
                }
                $id = $local_conn->delete($table);
                if($id) {
                    $remote_access = true;
                    break;
                }
            }
        }
        if ($remote_access) {
            logRemoteQuery($host, $local_conn->getLastQuery());
        }
    }

    if ($remote_access) {
        // insert row to other rows
        foreach ($nodes as $remote_host => $conn_data) {
            if ($local_host != $remote_host) {
                $conn = new mysqli($remote_host, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
                if ($conn->ping()) {
                    $remote_conn = new MysqliDb($conn);
                    foreach ($data['where'] as $w) {
                        count($w) < 3 ? $remote_conn->where($w[0], $w[1]) : $remote_conn->where($w[0], $w[1], $w[2]);
                    }
                    $id = $remote_conn->delete($table);
                }
                // check query execution
                if (!$conn->ping() || $id === -1) {
                    logRemoteQuery($remote_host, $local_conn->getLastQuery());
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
    $conn = new mysqli($local_host, $user, $password, $db);
    $remote_access = false;
    $id = false;
    // check local connection
    if ($conn->ping()) {
        $local_conn = new MysqliDb($conn);
        $id = $local_conn->insert($table, $data);
        if ($id)
            $remote_access = true;
    }

    if (!$id || !$conn->ping()) {
        $insert_errors_nodes = [];
        // find if any remote node is online
        foreach ($nodes as $node => $conn_data) {
            $local_host = $node;
            $conn = new mysqli($local_host, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
            if ($conn->ping()) {
                $local_conn = new MysqliDb($conn);
                $id = $local_conn->insert($table, $data);
                // check if insert was successful
                if ($id) {
                    $remote_access = true;
                    break;
                }
            }
        }

        // log local query
        if ($remote_access) {
            logRemoteQuery($host, $local_conn->getLastQuery());
        }
    }

    if ($remote_access) {
        // insert row to other rows
        foreach ($nodes as $remote_host => $conn_data) {
            if ($remote_host != $local_host) {
                $conn = new mysqli($remote_host, $conn_data['user'], $conn_data['password'], $conn_data['database_name']);
                if ($conn->ping()) {
                    $remote_conn = new MysqliDb($conn);
                    $id = $remote_conn->insert($table, $data);
                    if (!$id) {
                        logRemoteQuery($remote_host, $local_conn->getLastQuery());
                    }
                }
                // node connection problem
                else {
                    logRemoteQuery($remote_host, $local_conn->getLastQuery());
                }
            }
        }
    }
}
?>