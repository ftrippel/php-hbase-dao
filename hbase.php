<?

/**
 *
 * @author https://github.com/ftrippel
 *
 */

class AssertionFailedException extends Exception {}

function assertNotNull ($a) {
	if(!isset($a)) throw new AssertionFailedException();
}

function assertThat ($a) {
	if($a !== true) throw new AssertionFailedException();
}

use Thrift\Protocol\TBinaryProtocol;  
use Thrift\Transport\TSocket;  
use Thrift\Transport\TSocketPool;  
use Thrift\Transport\TFramedTransport;  
use Thrift\Transport\TBufferedTransport;

/**
 * @param string $host
 * @param int $port
 */
function get_thrift($class, $host, $port, $timeout){
	$socket = new Thrift\Transport\TSocket($host, $port);  
	$socket->setSendTimeout( $timeout );
	$socket->setRecvTimeout( $timeout );
	$transport = new TBufferedTransport($socket);  
	$transport->open();
	$protocol = new TBinaryProtocol($transport);  
	$client = new $class($protocol);  
	return $client;
}

/**
 * @param string $host
 * @param int $port
 */
function get_hbase($host, $port, $timeout=-1) {
	return get_thrift('HbaseClient', $host, $port, $timeout);
}
