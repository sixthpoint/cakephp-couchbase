<?php
/**
 * Couchbase Datasource class
 * @Author Brandon Klimek
 * 
 */
class CouchbaseSource extends DataSource {

    /**
     * Description of datasource
     *
     * @var string
     */
    public $description = 'Couchbase DataSource';

    /**
     * Holds the object for the connected database
     *
     * @var object
     */
    public $conObject = NULL;

    /**
     * Holds the configuration settings that are passed in
     *
     * @var array
     */
    public $config = NULL;

    /**
     * The prefix of the couchbase keys
     *
     * @var string
     */
    public $prefix = NULL;

    /**
     * CouchDBSource Constructor
     *
     * @param array $config The configuration for the Datasource
     *
     * @return void
     * @link http://api.cakephp.org/class/data-source#method-DataSource__construct
     */
    public function __construct($config = array()) {

        // If no configuration is set we use the default
        $this->config = $config;

        // Setup the cache string that is used when building the string
        $this->prefix = (isset($this->config['prefix']) ? $this->config['prefix'] . "_" : "");

        if ($this->config['autoConnect']) {
            $this->connect();
        }
    }

    /**
     * Connect to the Datasource
     *
     * @return obj
     * @throws InternalErrorException
     */
    public function connect() {
        if ($this->conObject !== true) {
            try {
                $this->conObject = new Couchbase("127.0.0.1:8091", $this->config['username'], $this->config['password'], $this->config['bucket'], $this->config['persistent']);
            } catch (CouchbaseException $e) {
                throw new InternalErrorException(array('class' => $e->getMessage()));
            }
        }
        return $this->conObject;
    }

    /**
     * Handle queries to couchbase
     *
     * @param unknown_type $method
     * @param array() $param
     * @return array or false
     */
    public function query($method, $params) {

        // If not connected... reconnect!
        if ($this->conObject === NULL) {
            $this->connect();
        }

        $apiMethod = $this->__methodToClass($method);
        if (!method_exists($this, $apiMethod)) {
            throw new NotFoundException("Class '{$apiMethod}' was not found");
        } else {
            return call_user_func_array(array($this, $apiMethod), $params);
        }
    }

    /**
     * Translate method to className
     *
     * @param $method
     * @return string
     */
    private function __methodToClass($method) {
        return 'CB' . strtolower(Inflector::camelize($method));
    }

    /**
     * describe() tells the model your schema for ``Model::save()``.
     *
     * You may want a different schema for each model but still use a single
     * datasource. If this is your case then set a ``schema`` property on your
     * models and simply return ``$Model->schema`` here instead.
     */
    public function describe(&$Model) {
        return $this->description;
    }

    /////////////////////////////////////////////////
    // Query Methods
    /////////////////////////////////////////////////

    /**
     * Add a value with the specified key that does not already exist. Will fail if the key/value pair already exist.
     *
     * @return Contains the document ID or false if the operation failed
     */
    public function CBadd($key = NULL, $value = NULL, $expiry = NULL, $persisto = NULL, $replicateto = NULL) {
        return $this->conObject->add($key, $value, $expiry, $persisto, $replicateto);
    }

    /**
     * Append a value to an existing key
     *
     * @return scalar ( Binary object )
     */
    public function CBappend($key = NULL, $value = NULL, $expiry = NULL, $persisto = NULL, $replicateto = NULL) {
        return $this->conObject->append($key, $value, $expiry, $persisto, $replicateto);
    }

    /**
     * Compare and set a value providing the supplied CAS key matches
     *
     * @return scalar ( Binary object )
     */
    public function CBcas($casimoqie = NULL, $key = NULL, $value = NULL, $expiry = NULL) {
        return $this->conObject->cas($casimoqie, $key, $value, $expiry);
    }

    /**
     * Decrement the value of an existing numeric key. The Couchbase Server stores numbers as unsigned values. Therefore the lowest you can decrement is to zero.
     *
     * @return scalar ( Binary object )
     */
    public function CBdecrement($key = NULL, $offset = NULL) {
        return $this->conObject->decrement($key, $offset);
    }

    /**
     * Delete a key/value
     *
     * @return scalar ( Binary object )
     */
    public function CBdelete($key = NULL, $offset = NULL) {
        $this->conObject->delete($key, $offset);
    }

    /**
     * Wait until the durability of a document has been reached
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBkeyDurability($key = NULL, $casunique = NULL) {
        return $this->conObject->keyDurability($key, $casunique);
    }

    /**
     * Wait until the durability of a document has been reached
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBflush() {
        return $this->conObject->flush();
    }

    /**
     * Get a value and update the expiration time for a given key
     *
     * @return obj
     */
    public function CBgetAndTouch($key = NULL, $expiry = NULL) {
        return $this->conObject->getAndTouch($key, $expiry);
    }

    /**
     * Get a value and update the expiration time for a given key
     *
     * @return obj
     */
    public function CBgetAndTouchMulti($key = NULL, $expiry = NULL) {
        return $this->conObject->getAndTouchMult($key, $expiry);
    }

    /**
     * Fetch the next delayed result set document
     *
     * @return array ( Result list )
     */
    public function CBfetch($key = NULL, $keyn = NULL) {
        return $this->conObject->fetch($key, $keyn);
    }

    /**
     * Fetch all the delayed result set documents
     *
     * @return array ( Result list )
     */
    public function CBfetchAll($key = NULL, $keyn = NULL) {
        return $this->conObject->fetchAll($key, $keyn);
    }

    /**
     * Get one or more key values
     *
     * @return scalar ( Binary object )
     */
    public function CBget($key = NULL, $callback = NULL, $casunique = NULL) {

        if (is_array($key)) {
            $key = $this->buildCacheString($key);
        }
        return $this->conObject->get($key, $callback, $casunique);
    }

    /**
     * Get one or more key values
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBgetDelayed($keyn = NULL, $with_cas = NULL, $callback = NULL) {
        return $this->conObject->getDelayed($keyn, $with_cas, $callback);
    }

    /**
     * Get one or more key values
     *
     * @return array ( Array of documents )
     */
    public function CBgetMulti($keycollection = NULL, $casarray = NULL) {
        return $this->conObject->getMulti($keycollection, $casarray);
    }

    /**
     * Returns the version of the client library
     *
     * @return scalar ( Binary object )
     */
    public function CBgetClientVersion() {
        return $this->conObject->getClientVersion();
    }

    /**
     * Get the value for a key, lock the key from changes
     *
     * @return (none)
     */
    public function CBgetAndLock($key = NULL, $casarray = NULL, $getlexpiry = NULL) {
        return $this->conObject->getAndLock($key, $casarray, $getlexpiry);
    }

    /**
     * Get the value for a key, lock the key from changes
     *
     * @return (none)
     */
    public function CBgetAndLockMulti($keycollection = NULL, $casarray = NULL, $getlexpiry = NULL) {
        return $this->conObject->getAndLockMulti($keycollection, $casarray, $getlexpiry);
    }

    /**
     * Returns the number of replicas for the configured bucket
     *
     * @return scalar ( Number of replicas )
     */
    public function CBgetNumReplicas() {
        return $this->conObject->getNumReplicas();
    }

    /**
     * Retrieve an option
     *
     * @return mixed ( Different possible types )
     */
    public function CBgetOption($option) {
        return $this->conObject->getOption($option);
    }

    /**
     * Returns the versions of all servers in the server pool
     *
     * @return array ( List of things )
     */
    public function CBgetVersion() {
        return $this->conObject->getVersion();
    }

    /**
     * Execute a view request
     *
     * @return (none)
     */
    public function CBview($ddocname = NULL, $viewname = NULL, $viewoptions = array()) {
        return $this->conObject->view($ddocname, $viewname, $viewoptions);
    }

    /**
     * Generate a view request, but do not execute the query
     *
     * @return (none)
     */
    public function CBviewGenQuery($ddocname = NULL, $viewname = NULL, $viewoptions = NULL) {
        return $this->conObject->viewGenQuery($ddocname, $viewname, $viewoptions);
    }

    /**
     * Increment the value of an existing numeric key. Couchbase Server stores numbers as unsigned numbers, therefore if 
     * you try to increment an existing negative number, it will cause an integer overflow and return a non-logical numeric 
     * result. If a key does not exist, this method will initialize it with the zero or a specified value.
     *
     * @return scalar ( Binary object )
     */
    public function CBincrement($key = NULL, $offset = NULL, $create = NULL, $expiry = NULL, $initial = NULL) {
        return $this->conObject->increment($key, $offset, $create, $expiry, $initial);
    }

    /**
     * Get the durability of a document
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBobserve($key = NULL, $casunique = NULL, $observeddetails = NULL) {
        return $this->conObject->observe($key, $casunique, $observeddetails);
    }

    /**
     * Get the durability of a document
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBobserveMulti($keycollection = NULL, $observeddetails = NULL) {
        return $this->conObject->observeMulti($keycollection, $observeddetails);
    }

    /**
     * Prepend a value to an existing key
     *
     * @return scalar ( Binary object )
     */
    public function CBprepend($key = NULL, $value = NULL, $expiry = NULL, $casunique = NULL, $persistto = NULL, $replicateto = NULL) {
        return $this->conObject->prepend($key, $value, $expiry, $casunique, $persistto, $replicateto);
    }

    /**
     * Update an existing key with a new value
     *
     * @return scalar ( Binary object )
     */
    public function CBreplace($key = NULL, $value = NULL, $expiry = NULL, $casunique = NULL, $persistto = NULL, $replicateto = NULL) {
        return $this->conObject->replace($key, $value, $expiry, $casunique, $persistto, $replicateto);
    }

    /**
     * Store a value using the specified key, whether the key already exists or not. Will overwrite a value if the given key/value already exists.
     *
     * @return scalar ( Binary object )
     */
    public function CBassign($key = NULL, $value = NULL, $expiry = NULL, $casunique = NULL, $persistto = NULL, $replicateto = NULL) {

        if (is_array($key)) {
            $key = $this->buildCacheString($key);
        }

        $time = (($expiry == NULL) ? $this->config['expiry'] : $expiry);

        try {
            $this->conObject->set($key, $value, $time, $casunique, $persistto, $replicateto);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Set multiple key/value items at once
     *
     * @return scalar ( Binary object )
     */
    public function CBassignMulti($kvarray = NULL, $expiry = NULL) {
        return $this->conObject->setMulti($kvarray, $expiry);
    }

    /**
     * Specify an option
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBsetOption($option = NULL, $mixed = NULL) {
        return $this->conObject->setOption($option, $mixed);
    }

    /**
     * Get the database statistics
     *
     * @return array ( List of things )
     */
    public function CBgetStats() {
        return $this->conObject->getStats();
    }

    /**
     * Update the expiry time of an item
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBtouch($key = NULL, $expiry = NULL) {
        return $this->conObject->touch($key, $expiry);
    }

    /**
     * Change the expiration time for multiple documents
     *
     * @return boolean ( Boolean (true/false) )
     */
    public function CBtouchMulti($keyarray = NULL, $expiry = NULL) {
        return $this->conObject->touchMulti($keyarray, $expiry);
    }

    /**
     * Private buildCacheString
     *
     * Takes in a array and then splits it into a string and underscores
     *
     * @param array resetSplit
     * @return string
     */
    private function buildCacheString($restSplit = array()) {

        $count = count($restSplit);
        $string = "";
        foreach ($restSplit as $key => $method) {
            $string .= Inflector::slug($method);
            if ($count - 1 != $key) {
                $string .= "_";
            }
        }
        return strtolower($this->prefix . $string);
    }

}
?>