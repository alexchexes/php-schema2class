<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Example;

/**
 * Minimal stub used by SchemaToClassTest to demonstrate how external classes can be referenced
 * via ReferenceLookup. The test does not execute this class; only the FQN is important.
 */
final class CustomerAddress {
  public static function buildFromInput()
  {
    return new self();
  }

  public function toArray()
  {
    return [];
  }

  public function toStdClass()
  {
    return new \stdClass;
  }
}