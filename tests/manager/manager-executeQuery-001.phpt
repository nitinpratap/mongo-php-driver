--TEST--
MongoDB\Driver\Manager::executeQuery() one document (OP_QUERY)
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"; ?>
<?php NEEDS("STANDALONE_30"); CLEANUP(STANDALONE_30); ?>
--FILE--
<?php
require_once __DIR__ . "/../utils/basic.inc";

$manager = new MongoDB\Driver\Manager(STANDALONE_30);

// load fixtures for test
$bulk = new MongoDB\Driver\BulkWrite();
$bulk->insert(array('_id' => 1, 'x' => 2, 'y' => 3));
$bulk->insert(array('_id' => 2, 'x' => 3, 'y' => 4));
$bulk->insert(array('_id' => 3, 'x' => 4, 'y' => 5));
$manager->executeBulkWrite(NS, $bulk);

$query = new MongoDB\Driver\Query(array('x' => 3), array('projection' => array('y' => 1)));
$qr = $manager->executeQuery(NS, $query);

var_dump($qr instanceof MongoDB\Driver\Cursor);
var_dump($qr);

$server = $qr->getServer();

var_dump($server instanceof MongoDB\Driver\Server);
var_dump($server->getHost());
var_dump($server->getPort());

var_dump(iterator_to_array($qr));

?>
===DONE===
<?php exit(0); ?>
--EXPECTF--
bool(true)
object(MongoDB\Driver\Cursor)#%d (%d) {
  ["database"]=>
  string(6) "phongo"
  ["collection"]=>
  string(32) "manager_manager_executeQuery_001"
  ["query"]=>
  object(MongoDB\Driver\Query)#%d (%d) {
    ["query"]=>
    object(stdClass)#%d (%d) {
      ["$query"]=>
      object(stdClass)#%d (%d) {
        ["x"]=>
        int(3)
      }
    }
    ["selector"]=>
    object(stdClass)#%d (%d) {
      ["y"]=>
      int(1)
    }
    ["flags"]=>
    int(0)
    ["skip"]=>
    int(0)
    ["limit"]=>
    int(0)
    ["batch_size"]=>
    int(0)
    ["readConcern"]=>
    NULL
  }
  ["command"]=>
  NULL
  ["readPreference"]=>
  NULL
  ["isDead"]=>
  bool(false)
  ["currentIndex"]=>
  int(0)
  ["currentDocument"]=>
  NULL
  ["server"]=>
  object(MongoDB\Driver\Server)#%d (%d) {
    %a
  }
}
bool(true)
string(%d) "%s"
int(%d)
array(1) {
  [0]=>
  object(stdClass)#%d (2) {
    ["_id"]=>
    int(2)
    ["y"]=>
    int(4)
  }
}
===DONE===
