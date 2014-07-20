<?

/**
 *
 * @author https://github.com/ftrippel
 *
 */
class PersonImage {
	
	protected $id;
	protected $personId;
	protected $createdAt;
	protected $uploaderIp;
	protected $uploaderId;
	protected $isVisible;

	public function __construct() {
	}

	public function getId() {
		return $this->id;
	}

	public function getPersonId() {
		return $this->personId;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function getUploaderIp() {
		return $this->uploaderIp;
	}

	public function getUploaderId() {
		return $this->uploaderId;
	}

	public function getIsVisible() {
		return $this->isVisible;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setPersonId($personId) {
		$this->personId = $personId;
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	public function setUploaderIp($uploaderIp) {
		$this->uploaderIp = $uploaderIp;
	}

	public function setUploaderId($uploaderId) {
		$this->uploaderId = $uploaderId;
	}

	public function setIsVisible($isVisible) {
		$this->isVisible = $isVisible;
	}
}

