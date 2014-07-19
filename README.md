php-hbase-dao
=============

* Design Goal
  * A simple and flexible Dao-Layer for HBase
  * Manual mapping of entity properties to HBase mutations and vice versa
* Features
  * Create, Drop for Tables
  * Save, Remove, Find (single or range), Scan (`TScan`) for Entities
* Requirements
  * PHP thrift library
  * PHP thrift client for HBase

# Example

```php
$personDao = new PersonDao_HBase();
$personDao->create(); // creates table

$person = new Person();
// ... set $person properties
$personDao->save($person); // saves row

$personDao->remove($person); // deletes row

$personDao->drop(); // drops table
```
