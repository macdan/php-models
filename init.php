<?php

require_once 'Doctrine/Core.php';
spl_autoload_register( array( 'Doctrine_Core', 'autoload' ) );

spl_autoload_register( function( $class )
{
	$file = str_replace( '_', '/', $class ) . '.php';
	require_once $file;
} );

$db = Doctrine_Manager::connection( 'sqlite:///' . dirname( __FILE__ ) . '/db/database.db', 'db' );

require_once 'Dao.php';
require_once 'Model.php';
