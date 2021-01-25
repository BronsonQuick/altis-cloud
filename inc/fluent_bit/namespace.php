<?php
/**
 * Altis Cloud Fluent Bit logger.
 *
 * @package altis/cloud
 */

namespace Altis\Cloud\Fluent_Bit;

use Altis\Cloud\Fluent_Bit\MsgPackFormatter;
use Monolog\Handler\SocketHandler;
use Monolog\Logger;

/**
 * Check if required constants have been defined
 *
 * @return boolean
 */
function is_available() {
	return defined( 'FLUENT_HOST' ) && defined( 'FLUENT_PORT' );
}

/**
 * Retrieve logger for specied $tag_name
 *
 * @param string $tag_name Name of the tag to route log messages to
 * @return Monolog\Logger
 */
function get_logger( string $tag_name ) {
	// Let's store each logger in an array so that we don't keep instantiating
	// loggers. We create a new logger for each Monolog channel. The channel
	// name will be used as the Fluent Bit tag.
	static $loggers = [];

	if ( $loggers[ $tag_name ] ) {
		return $loggers[ $tag_name ];
	}

	$logger = new Logger( $tag_name );

	// Use Fluent Bit if it's available.
	if ( is_available() ) {
		$socket = new SocketHandler( FLUENT_HOST . ':' . FLUENT_PORT, Logger::DEBUG );
		$socket->setFormatter( new MsgPackFormatter() );
		$logger->pushHandler( $socket );
	} else {
		trigger_error( 'Fluent Bit is not available. Logs will not be routed anywhere.', E_USER_WARNING );
	}

	$loggers[ $tag_name ] = $logger;
	return $loggers[ $tag_name ];
}
