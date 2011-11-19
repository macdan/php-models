<?php

/**
 * Mapper Base Class
 */
class Mapper
{
	/**
	 * Magic Call
	 *
	 * Handles get*Mapper() calls
	 *
	 * @param  string $property The property to set
	 * @param  array  $params The parameters the method was called with
	 * @return mixed  The method return value
	 */
	public function __call( $method, $params )
	{
		if ( preg_match( '/get(.+?)Mapper/', $method, $matches ) )
		{
			$model = $matches[1];
			
			if ( !isset( $this->_mappers[ $model ] ) )
			{
				$class = "Mapper_{$model}";
				$this->_mappers[ $model ] = new $class;
			}
			
			return $this->_mappers[ $model ];
		}
		
		throw new Exception( "Unknown method: {$method}" );
	}
}

/**
 * Product Mapper
 */
class Mapper_Product extends Mapper
{
	/**
	 * Find
	 *
	 * Given the details contained within the domain model, find and load 
	 * the rest of the data into it. The Domain Model instance only needs 
	 * the properties required to find it. In this case, both an ID and CLDR
	 * are required. The ID tells us which product record we want, and the 
	 * CLDR states the locale for which we want any localised data.
	 *
	 * @param Model_Product $product Domain Model
	 */
	public function find( Model_Product $product )
	{
		$this->_mapProduct( $product, $this->_loadProduct( $product ) );
		$this->_mapL10n( $product, $this->_loadL10n( $product ) );
		
		$this->_proxyImages( $product );
		
		return $product;
	}
	
	/**
	 * Map Product
	 *
	 * Moves data from the Product DAO into the Product DM
	 *
	 * @param Model_Product $product Domain Model
	 * @param Dao_Product   $dao     Data Access Object
	 */
	protected function _mapProduct( Model_Product $product, Dao_Product $dao )
	{
		$product->fromArray( array(
			'id' => $dao->id,
			'sku' => $dao->sku
		) );
	}
	
	/**
	 * Map L10N
	 *
	 * Moves data from the ProductL10n DAO into the Product DM
	 *
	 * @param Model_Product   $product Domain Model
	 * @param Dao_ProductL10n $dao     Data Access Object
	 */
	protected function _mapL10n( Model_Product $product, Dao_ProductL10n $dao )
	{
		$product->fromArray( array(
			'cldr' => $dao->cldr,
			'title' => $dao->title,
			'summary' => $dao->summary
		) );
	}
	
	/**
	 * Load Product
	 *
	 * Fetches the Product DAO from the database using details contained
	 * within the domain model
	 *
	 * @param  Model_Product $product Domain Model
	 * @return Dao_Product   $dao     Data Access Object
	 */
	protected function _loadProduct( Model_Product $product )
	{
		if ( !$product->id )
		{
			throw new Exception( 'Need ID' );
		}
		
		return Dao::gimmieProduct( $product->id );
	}
	
	/**
	 * Load L10N
	 *
	 * Fetches the ProductL10n DAO from the database using details contained
	 * within the domain model
	 *
	 * @param  Model_Product   $product Domain Model
	 * @return Dao_ProductL10n $dao     Data Access Object
	 */
	protected function _loadL10n( Model_Product $product )
	{
		if ( !$product->id )
		{
			throw new Exception( 'Need ID' );
		}
		
		if ( !$product->cldr )
		{
			throw new Exception( 'Need CLDR' );
		}
		
		return Dao::gimmieProduct( $product->id, $product->cldr );
	}
	
	/**
	 * Proxy Images
	 * 
	 * Finds the IDs of the related images, prepares proxy Image models
	 * and assigns them to the Product model
	 * 
	 * @param Model_Product $product Domain Model
	 */
	protected function _proxyImages( Model_Product $product )
	{
		$dao = $this->_loadProduct( $product );
		
		$images = array();
		
		foreach ( $dao->images as $daoImage )
		{
			$images[] = new Model_Image( array(
				'_mapper' => $this->getImageMapper(),
				'id' => $daoImage->image_id,
				'cldr' => $product->cldr
			) );
		}
		
		$product->images = $images;
	}
}

/**
 * Image Mapper
 */
class Mapper_Image extends Mapper
{
	public function find( Model_Image $image )
	{
		$image->fromArray( array(
			'id' => 1,
			'cldr' => 'en_GB',
			'src' => 'http://example.com/image.png'
		) );
	}
}

