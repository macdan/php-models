<?php

/**
 * Dao.php
 *
 * This file contains all the dummy DAO classes. 
 * These are ludicrously oversimplified and in reality 
 * would interact with your database. 
 *
 * I recommend Doctrine ORM for this role.
 * @see http://www.doctrine-project.org/
 */

/**
 * Dummy DAO base class
 * 
 * Also statically acts as the data store.
 */
class Dao
{
	public static function gimmieProduct( $id, $cldr=null )
	{
		if ( $id == 1 )
		{
			if ( $cldr == 'en_GB' )
			{
				return new Dao_ProductL10n( array(
					'product_id' => 1,
					'cldr' => 'en_GB',
					'title' => 'My Product',
					'summary' => "This product is so incredible, words just can't describe it!"
				) );
			}
			
			return new Dao_Product( array(
				'id' => 1,
				'sku' => "OMG-PROD",
				'images' => array(
					(object) array(
						'product_id' => 1,
						'image_id' => 1
					),
					(object) array(
						'product_id' => 1,
						'image_id' => 2
					),
				)
			) );
		}
		
		throw new Exception( "nope" );
	}
	
	public function gimmieImage( $id )
	{
		switch ( $id )
		{
			case 1:
				return new Dao_Image( array(
					'id' => 1,
					'src' => 'http://imgs.xkcd.com/comics/wisdom_of_the_ancients.png'
				) );
				break;
			
			case 2:
				return new Dao_Image( array(
					'id' => 2,
					'src' => 'http://imgs.xkcd.com/comics/the_general_problem.png'
				) );
				break;
			
			default:
				throw new Exception( "nope" );
		}
	}
	
	/**
	 * Construct
	 * 
	 * Populate the DAO object with data from the array
	 */
	public function __construct( array $array=array() )
	{
		foreach ( $array as $key => $value )
		{
			if ( !property_exists( $this, $key ) )
			{
				continue;
			}
			
			$this->$key = $value;
		}
	}
}

/**
 * Product DAO
 */
class Dao_Product extends Dao
{
	public $id = null;
	public $sku = null;
	public $images = null;
}

/**
 * Product Localisation DAO
 */
class Dao_ProductL10n extends Dao
{
	public $product_id = null;
	public $cldr = null;
	public $title = null;
	public $summary = null;
}

/**
 * Image DAO
 */
class Dao_Image extends Dao
{
	public $id = null;
	public $src = null;
}
