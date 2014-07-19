<?

/**
 *
 * @author ftrippel
 *
 */
class PersonDao_HBase extends Dao_HBase {

	public function __construct($table='person') {
		parent::__construct($table, 'Person');
	}

	public function getHBaseSchema() {
		$columns = array(
			new ColumnDescriptor( array(
				'name' => 'text:',
				'compression' => 'GZ',
				'maxVersions' => 1,
				'inMemory' => false,
				'blockCacheEnabled' => false
			) )
		);
		return $columns;
	}

	public function toHBase($p) {
		$d = array();
		foreach($p->getPayload() as $provider=>$payload) {
			$d[] = new Mutation(array(
				'column' => 'text:'.$provider,
				'value' => json_encode($payload)
			));
		}
		return $d;
	}

	public function toHBaseKey($p) {
		$s = $p->getId();
		return $s;
	}

	public function toClass(TRowResult $d, $class) {
		$p = new $class();
		foreach($d->columns as $provider=>$payload) {
			$p->setPayload(str_replace('text:','',$provider), json_decode($payload->value,true));
		}
		return $p;
	}

}

