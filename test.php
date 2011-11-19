#!/usr/bin/php
<?php

require_once 'init.php';

$mapper = new Mapper_Product;

$product = new Model_Product( array(
	'id' => 1,
	'cldr' => 'en_GB'
) );

assert( $product->sku == null );
assert( $product->title == null );

$mapper->find( $product );

assert( $product->sku == "OMG-PROD" ); 
assert( $product->title == "My Product" );

$images = $product->images;
$image = array_shift( $images );

echo $image->src, "\n";

