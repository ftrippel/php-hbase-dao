<?

/**
 *
 * @author https://github.com/ftrippel
 *
 */
class PersonImageDao_HBase extends Dao_HBase {

	public function __construct($table='person_image') {
		parent::__construct($table, 'PersonImage');
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
  		$d[] = new Mutation(array(
			'column' => 'text:id',
			'value' => $p->getId()
		));
  		$d[] = new Mutation(array(
			'column' => 'text:personId',
			'value' => $p->getPersonId()
		));
  		$d[] = new Mutation(array(
			'column' => 'text:createdAt',
			'value' => $p->getCreatedAt()
		));
  		$d[] = new Mutation(array(
			'column' => 'text:uploaderId',
			'value' => $p->getUploaderId()
		));
  		$d[] = new Mutation(array(
			'column' => 'text:uploaderIp',
			'value' => $p->getUploaderIp()
		));
  		$d[] = new Mutation(array(
			'column' => 'text:isVisible',
			'value' => $p->getIsVisible()
		));
		return $d;
	}

	public function toHBaseKey($p) {
		$s = $p->getPersonId();
		$t = $p->getId();
		if(!empty($t)) {
			$s = $s.':'.$t;
		}
		return $s;
	}

	public function toClass(TRowResult $d, $class) {
		$p = new $class();
		$p->setId($d->columns['text:id']->value);
		if(isset($d->columns['text:personId'])) {
			$p->setPersonId($d->columns['text:personId']->value);
		}
		if(isset($d->columns['text:createdAt'])) {
			$p->setCreatedAt($d->columns['text:createdAt']->value);
		}
		if(isset($d->columns['text:uploaderId'])) {
			$p->setUploaderId($d->columns['text:uploaderId']->value);
		}
		if(isset($d->columns['text:uploaderIp'])) {
			$p->setUploaderIp($d->columns['text:uploaderIp']->value);
		}
		if(isset($d->columns['text:isVisible'])) {
			$p->setIsVisible($d->columns['text:isVisible']->value);
		}
		return $p;
	}

}

