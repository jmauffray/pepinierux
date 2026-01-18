<?php
// MySQL Shim for PHP 8.x compatibility
// Maps deprecated mysql_* functions to mysqli_* equivalents

$global_link = null;

if (!function_exists('mysql_connect')) {
    function mysql_connect($server, $username, $password, $new_link = false, $client_flags = 0) {
        global $global_link;
        $global_link = mysqli_connect($server, $username, $password);
        return $global_link;
    }
}

if (!function_exists('mysql_select_db')) {
    function mysql_select_db($database_name, $link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        return mysqli_select_db($link, $database_name);
    }
}

if (!function_exists('mysql_query')) {
    function mysql_query($query, $link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        return mysqli_query($link, $query);
    }
}

if (!function_exists('mysql_fetch_array')) {
    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
        return mysqli_fetch_array($result, $result_type);
    }
}

if (!function_exists('mysql_fetch_assoc')) {
    function mysql_fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }
}

if (!function_exists('mysql_fetch_row')) {
    function mysql_fetch_row($result) {
        return mysqli_fetch_row($result);
    }
}

if (!function_exists('mysql_fetch_object')) {
    function mysql_fetch_object($result, $class_name = "stdClass", $params = []) {
        return mysqli_fetch_object($result, $class_name, $params);
    }
}

if (!function_exists('mysql_num_rows')) {
    function mysql_num_rows($result) {
        return mysqli_num_rows($result);
    }
}

if (!function_exists('mysql_insert_id')) {
    function mysql_insert_id($link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        return mysqli_insert_id($link);
    }
}

if (!function_exists('mysql_error')) {
    function mysql_error($link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        return mysqli_error($link);
    }
}

if (!function_exists('mysql_close')) {
    function mysql_close($link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        return mysqli_close($link);
    }
}

if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($unescaped_string, $link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        return mysqli_real_escape_string($link, $unescaped_string);
    }
}

if (!function_exists('mysql_data_seek')) {
    function mysql_data_seek($result, $row_number) {
        return mysqli_data_seek($result, $row_number);
    }
}

if (!function_exists('mysql_num_fields')) {
    function mysql_num_fields($result) {
        return mysqli_num_fields($result);
    }
}

if (!function_exists('mysql_field_name')) {
    function mysql_field_name($result, $field_offset) {
        $field_info = mysqli_fetch_field_direct($result, $field_offset);
        return $field_info ? $field_info->name : false;
    }
}

// mysql_result is tricky as it's not directly in mysqli. 
// Implementing a basic version.
if (!function_exists('mysql_result')) {
    function mysql_result($result, $row, $field = 0) {
        if (mysqli_num_rows($result) == 0) return false;
        mysqli_data_seek($result, $row);
        $data = mysqli_fetch_array($result);
        if (is_numeric($field)) {
             $keys = array_keys($data);
             // array_keys returns numeric keys and string keys. 
             // mysqli_fetch_array with MYSQLI_BOTH (default) returns both.
             // We need to be careful.
             // simpler: use fetch_row if field is numeric
             if (!isset($data[$field])) return false;
             return $data[$field];
        } else {
            if (!isset($data[$field])) return false;
            return $data[$field];
        }
    }
}

// mysql_db_query: Selects a database and executes a query on it.
if (!function_exists('mysql_db_query')) {
    function mysql_db_query($database, $query, $link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        mysqli_select_db($link, $database);
        return mysqli_query($link, $query);
    }
}

// mysql_list_tables: List tables in a MySQL database
if (!function_exists('mysql_list_tables')) {
    function mysql_list_tables($database, $link_identifier = null) {
        global $global_link;
        $link = $link_identifier ? $link_identifier : $global_link;
        return mysqli_query($link, "SHOW TABLES FROM `{$database}`");
    }
}

if (!function_exists('mysql_tablename')) {
    function mysql_tablename($result, $i) {
        // This is usually used with mysql_list_tables result
        if (mysqli_data_seek($result, $i)) {
             $row = mysqli_fetch_row($result);
             return $row[0];
        }
        return false;
    }
}

?>
