<?php
namespace Lucinda\NoSQL;

/**
 * Exception thrown when a set/delete is performed on a key that doesn't exist.
 */
class KeyNotFoundException extends \Exception {}