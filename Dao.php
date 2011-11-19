<?php

/**
 * Dummy DAOs
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
}

class Dao_Product extends Dao
{
	public $id = null;
	public $sku = null;
	
	public $images = null;
}

class Dao_ProductL10n extends Dao
{
	public $cldr = null;
	public $title = null;
	public $summary = null;
}

