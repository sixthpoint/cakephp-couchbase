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
        'username' => 'username',
        'password' => 'password',
        'bucket' => 'yourBucket',
        'prefix' => '',
        'expiry' => '1814400', //3 Weeks
        'autoConnect' => true,
        'database' => NULL,
        'persistent' => false
    );
```


Usage
------------


In the model of your choice specify the name of the couchbase datasource object

```php
class Sample extends AppModel {

    /**
     * Name of the model
     */
    public $name = 'Sample';

    /**
     * Database to use
     */
    public $useDbConfig = 'defaultCB';

}
```

Now from your controllers you can use the datasource API

```php

// Try again to get the cached data based on key, if not assign the data to the key
$cache = $this->Controller->Get(array($key));
if ($cache) {
    $this->Controller->Assign(array($key), json_encode($data));
}

```

