CakePHP - Couchbase Datasource
=======

The following is a simple datasource solution for couchbase server 2. This is intended to work with CakePHP 2.x

Background
----------------

Sometimes your app has the need for large data sets, and requires the flexibility of NoSQL to accomplish this. There is no couchbase datasource available / and or they are overly complicated. This is the simple solution.

Requirements
------------

* CakePHP 2.x
* Couchbase Server 2.x
* PHP5

Installation
------------

Begin by installing couchbase [http://www.couchbase.com/download](http://www.couchbase.com/download)

Once installed you can create a bucket of your choice. In this case "queriesCB" is my example. Give it a port number and password to access. 

Tell cocuhbase you have a custom datasource in the core/database.php

```php
    public $defaultCB = array(
        'datasource' => 'CouchbaseSource',
        'username' => 'church',
        'password' => '',
        'bucket' => 'yourBucket',
        'prefix' => '',
        'expiry' => '1814400', //3 Weeks
        'autoConnect' => true,
        'database' => NULL,
        'persistent' => false
    );
```

Insert / merge the contents of core/config.php into your app. The important line is that the cache has access to the queriesCB object.

```php
Cache::config('queriesCB', array(
    'engine' => $engine, //[required]
    'duration' => '+4 weeks', //[optional]
    'probability' => 100, //[optional]
    'password' => '',
    'prefix' => 'q_', //[optional]  prefix every cache file with this string
    'servers' => array(
        '127.0.0.1:11220' // localhost, default port 11211
    ), //[optional]
    'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
    'persistent' => false, // [optional] set this to false for non-persistent connections
    'autoConnect' => false
));
```


Usage
------------


todo write
