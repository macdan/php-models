#!/usr/bin/php
<?php

require_once 'init.php';

/**
 * Create an instance of the product mapper
 */
$mapper = new Mapper_Product;

/**
 * Create a ghost model
 */
$product = new Model_Product( array(
	'id' => 1,
	'cldr' => 'en_GB'
) );

/**
 * Assert that this is a ghost model
 */
assert( $product->id == 1 ); // We know the ID...
assert( $product->title == null ); // ... but not the title.

/**
 * *pulls knifeswitch* It's alive! Mwahahahaha! (...find the model)
 */
$mapper->find( $product );

/**
 * Assert that we've got the rest of the data
 */
assert( $product->title == "My Product" ); // The title was loaded...
assert( count( $product->images ) == 2 ); // ...so were the images!

/**
 * And why not a bit of shell output?
 */
echo $product->title, ' (', $product->stock, ')', "\r\n";

foreach ( $product->images as $image )
{
	echo "\tImg: ", $image->src, "\r\n";
}

