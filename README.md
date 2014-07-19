php-hbase-dao
=============

* Requirements
  * PHP thrift library
  * PHP thrift client for HBase
* Features
  * Create, Drop for Tables
  * Save, Remove, Find (single or range), Scan (TScan) for Entities
* Design Goal
  * A simple and flexible Dao-Layer for HBase
  * Mapping of object properties to HBase mutations and vice versa have to be done manually

# Example

```php
$personDao = new PersonDao_HBase();
$personDao->create(); // creates HBase table

$person = new Person();
// ... set $person properties
$personDao->save($person); // saves row

$personDao->remove($person); // deletes row

$personDao->drop(); // drops HBase table
```
