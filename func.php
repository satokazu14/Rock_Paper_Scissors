<?php
function fetch_all(&$stmt){
  $hits = array();
  $params = array();
  $row = array();
  $meta = $stmt->result_metadata();
  while ($field = $meta->fetch_field()) {
    $params[] = &$row[$field->name];
  }
  call_user_func_array(array($stmt, 'bind_result'), $params);
  while ($stmt->fetch()) {
    $c = array();
    foreach ($row as $key => $val) {
      $c[$key] = $val;
    }
    $hits[] = $c;
  }
  return $hits;
}
