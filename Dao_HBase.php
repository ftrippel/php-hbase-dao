<?

/**
 *
 * @author https://github.com/ftrippel
 *
 */

require_once 'hbase.php';

class Dao_HBase_Cursor implements Iterator {
	private $dao;
	private $hbase;
	private $scanner;
	private $class;

	private $i = -1;
	private $current;
	private $valid = true;

	public function __construct($hbase, $scanner, $class, Dao_HBase $dao) {
		$this->hbase = $hbase;
		$this->scanner = $scanner;
		$this->class = $class;
		$this->dao = $dao;
		$this->next();
	}

	public function __destruct() {
		$this->hbase->scannerClose($this->scanner);
	}

	public function rewind() {
		$this->_rewind++;
		if($this->_rewind>1) {
			throw new Exception("You can traverse this collection only once");
		}
	}	

	public function current() {
		return $this->current;
	}

	public function key() {
		return $this->i;
	}

	public function next() {
		$d = $this->hbase->scannerGetList($this->scanner, 1);
		if(empty($d)) {
			$this->valid = false;
			return NULL;
		}
		$d = $d[0];
		if(empty($d)) {
			$this->valid = false;
			return NULL;
		}
		$this->i++;
		$this->current = $this->dao->toClass($d, $this->class);
		return $this->current;
	}

	public function valid() {
		return $this->valid;
	}

}

abstract class Dao_HBase {

	protected $h;
	protected $table;
	protected $class;

	public function __construct($table, $class) {
		$this->table = $table;
		$this->class = $class;
		$this->h = get_hbase('localhost', 9090, 10000000);
	}

	public function drop() {
		if(in_array($this->table, $this->h->getTableNames())){
			if($this->h->isTableEnabled($this->table)){
				$this->h->disableTable($this->table);
			}
			$this->h->deleteTable($this->table);
		}
	}

	public function create() {
		if(!in_array($this->table, $this->h->getTableNames())) {
			$this->h->createTable($this->table, $this->getHBaseSchema());
		}
	}

	/**
	 * @return array[ColumnDescriptor]
	 */
	public abstract function getHBaseSchema();

	/**
	 * @param object
	 * @return array[Mutation]
	 */
	public abstract function toHBase($o);

	/**
	 * @param object
	 * @return string
	 */
	public abstract function toHBaseKey($o);

	/**
	 * @param TRowResult
	 * @param string
	 * @return object
	 */
	public abstract function toClass(TRowResult $d, $class);

	public function assertClass($o) {
		if(!is_a($o, $this->class)) {
			throw new Exception('expected class '.$this->class.', got class '.get_class($o)); 
		}
	}

	public function find($o) {
		$this->assertClass($o);
		$d = $this->h->getRow($this->table, $this->toHBaseKey($o));
		if(empty($d)) return NULL;
		return $this->toClass($d[0], get_class($o));
	}

	public function save($o) {
		$this->assertClass($o);
		$cols = $this->toHBase($o);
		$key = $this->toHBaseKey($o);
		assertNotNull($key);
		assertThat(is_array($cols));
		$this->h->mutateRow($this->table, $key, $cols);
	}

	public function remove($o){
		$this->assertClass($o);
		$key = $this->toHBaseKey($o);
		assertNotNull($key);
		$this->h->deleteAllRow($this->table, $key);
	}

	public function findAll($o=NULL, $columns=NULL) {
		if(isset($o)) {
			$this->assertClass($o);
			$class = get_class($o);
			$scanner = $this->h->scannerOpenWithPrefix($this->table, $this->toHBaseKey($o), $columns);
		} else {
			$class = $this->class;
			$scanner = $this->h->scannerOpen($this->table, "", $columns);
		}
		return new Dao_HBase_Cursor($this->h, $scanner, $class, $this);
	}

	public function scan(\TScan $scan, $class=NULL) {
		if(!isset($class)) {
			$class = $this->class;
		}
		$scanner = $this->h->scannerOpenWithScan($this->table, $scan);
		return new Dao_HBase_Cursor($this->h, $scanner, $class, $this);
	}

	public function removeAll($o=NULL) {
		foreach( $this->findAll($o) as $result ) {
			$this->remove($result);
		}
	}

	public function count($o=NULL) {
		return iterator_count($this->findAll($o, ['text:id']));
	}

}

