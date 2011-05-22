<?php

include('underscore.php');

class UnderscoreTest extends PHPUnit_Framework_TestCase {
  
  public function testMap() {
    $iterator = function($val, $key=null) { return $val * 3; };
    $collection = array('one'=>1, 'two'=>2, 'three'=>3);
    $expected = array(3, 6, 9);
    
    // Array: keyed
    $this->assertEquals($expected, _::map($collection, $iterator));
    
    // Array: zero indexed
    $zero_indexed = array_values($collection);
    $this->assertEquals($expected, _::map($zero_indexed, $iterator));
    
    // Object
    $collection_obj = (object) $collection;
    $this->assertEquals($expected, _::map($collection_obj, $iterator));
  }
  
  public function testPluck() {
    // Array
    $stooges = array(
      array('name'=>'moe',   'age'=> 40),
      array('name'=>'larry', 'age'=> 50),
      array('name'=>'curly', 'age'=> 60)
    );
    $tests = array(
      'name'=> array('moe', 'larry', 'curly'),
      'age' => array(40, 50, 60)
    );
    foreach($tests as $key=>$expected) {
      $this->assertEquals($expected, _::pluck($stooges, $key));
    }
    
    // Object
    $stooges_obj = new StdClass;
    foreach($stooges as $stooge) {
      $name = $stooge['name'];
      $stooges_obj->$name = (object) $stooge;
    }
    foreach($tests as $key=>$expected) {
      $this->assertEquals($expected, _::pluck($stooges_obj, $key));
    }
  }
  
  public function testIncludes() {
    $collection = array(true, false, 0, 1, -1, 'foo', array(), array('meh'));
    $tests = array(
      // val, expected
      array(true, true),
      array(false, true),
      array(0, true),
      array(1, true),
      array(-1, true),
      array('foo', true),
      array(array(), true),
      array(array('meh'), true),
      array('true', false),
      array('0', false),
      array('1', false),
      array('-1', false),
      array('bar', false),
      array('Foo', false)
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::includes($collection, $test[0]));
    }
  }
  
  public function testAny() {
    $tests = array(
      // val, expected
      array(array(), false),
      array(array(null), false),
      array(array(0), false),
      array(array('0'), false),
      array(array(0, 1), true),
      array(array(1), true),
      array(array('1'), true),
      array(array(1,2,3,4), true)
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::any($test[0]));
    }
  }
  
  public function testAll() {
    $tests = array(
      // val, expected
      array(array(), true),
      array(array(null), false),
      array(array(0), false),
      array(array('0'), false),
      array(array(0, 1), false),
      array(array(1), true),
      array(array('1'), true),
      array(array(1,2,3,4), true)
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::all($test[0]));
    }
  }
  
  public function testSelect() {
    $iterator = function($n) { return $n % 2 === 0; };
    $tests = array(
      // val, expected
      array(array(1, 2, 3, 4, 5, 6), array(2, 4, 6))
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::select($test[0], $iterator));
    }
  }
  
  public function testReject() {
    $iterator = function($n) { return $n % 2 === 0; };
    $tests = array(
      // val, expected
      array(array(1, 2, 3, 4, 5, 6), array(1, 3, 5))
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::reject($test[0], $iterator));
    }
  }
  
  public function testDetect() {
    $iterator = function($n) { return $n % 2 === 0; };
    $tests = array(
      // val, expected
      array(array(1, 2, 3, 4, 5, 6), 2),
      array(array(1, 3, 5), false)
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::detect($test[0], $iterator));
    }
  }
  
  public function testSize() {
    $tests = array(
      // val, expected
      array(array(), 0),
      array(array(1), 1),
      array(array(1, 2, 3), 3)
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::size($test[0]));
    }
  }
  
  public function testFirst() {
    $tests = array(
      // val, expected, n
      array(array(), null),
      array(array(null), null),
      array(array(0), 0),
      array(array('0'), '0'),
      array(array(0, 1), 0),
      array(array(1), 1),
      array(array('1'), '1'),
      array(array(1,2,3,4), 1),
      array(array(1,2,3,4), array(1, 2), 2),
      array(array(1,2,3,4), array(1, 2, 3, 4), 100),
    );
    foreach($tests as $test) {
      $n = (count($test) === 3) ? $test[2] : 1;
      $this->assertEquals($test[1], _::first($test[0], $n));
    }
  }
  
  public function testRest() {
    $tests = array(
      // val, expected, n
      array(array(), array()),
      array(array(null), array()),
      array(array(0), array()),
      array(array('0'), array()),
      array(array(0, 1), array(1)),
      array(array(1), array()),
      array(array('1'), array()),
      array(array(1,2,3,4), array(2,3,4)),
      array(array(1,2,3,4), array(3, 4), 2),
      array(array(1,2,3,4), array(), 100),
    );
    foreach($tests as $test) {
      $index = (count($test) === 3) ? $test[2] : 1;
      $this->assertEquals($test[1], _::rest($test[0], $index));
    }
  }
  
  public function testLast() {
    $tests = array(
      // val, expected, n
      array(array(), null),
      array(array(null), null),
      array(array(0), 0),
      array(array('0'), '0'),
      array(array(0, 1), 1),
      array(array(1), 1),
      array(array('1'), '1'),
      array(array(1,2,3,4), 4)
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::last($test[0]));
    }
  }
  
  public function testCompact() {
    $tests = array(
      // val, expected
      array(array(0, 1, 2), array(1, 2)),
      array(array(0, 1, false, 2), array(1, 2)),
      array(array(null), array()),
      array(array('0', array(), 1), array(1))
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::compact($test[0]));
    }
  }
  
  public function testFlatten() {
    $tests = array(
      // val, expected
      array(array(0, 1, 2), array(0, 1, 2)),
      array(array(array(0, 1, 2), array(3, 4, 5)), array(0, 1, 2, 3, 4, 5)),
      array(array(array(0, array(1, 2, 3), array(4, 5))), array(0, 1, 2, 3, 4, 5))
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::flatten($test[0]));
    }
  }
  
  public function testWithout() {
    $tests = array(
      // val, expected
      array(array(0, 1, 2), array(0, 2=>2)),
      array(array(true, false, 0, 1, 2, '1'), array(1=>false, 2=>0, 4=>2))
    );
    foreach($tests as $test) {
      $this->assertEquals($test[1], _::without($test[0], 1, '1', true));
    }
  }
}