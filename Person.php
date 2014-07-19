<?

/**
 *
 * @author ftrippel
 *
 */
class Person {

	protected $id;
	protected $payload = [];

	public function __construct() {
	}

	public function getId() {
		return $this->id;
	}

	public function setPayload($provider, $payload) {
		$this->id = $payload['_id'];
		$this->payload[$provider] = $payload;
	}

	public function getPayload($provider=null) {
		if(isset($provider)) {
			if(isset($this->payload[$provider])) {
				return $this->payload[$provider];
			} else {
				return null;
			}
		} else {
			return $this->payload;
		}
	}

}

