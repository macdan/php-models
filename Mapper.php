<?php

/**
 * Dk_Model_Mapper_Doctrine_Product
 *
 * The Product mapper, using Doctrine as the DAL.
 */
class Mapper_Doctrine_Product extends Dk_Model_Mapper_Doctrine
{
	/**
	 * @var string The Domain Model class name
	 */
	protected $_domainModelClass = 'Model_Product';
	
	/**
	 * Map
	 *
	 * The main Mapping function.
	 *
	 * @param Model_Dao_Product $source The Data Access Object (data source)
	 * @param Model_Product $destination The Domain Model (data destination)
	 * @returns Model_Product The same destination object, but with the data from $source mapped into it
	 */
	protected function _map( Model_Dao_Product $source, Model_Product $destination )
	{
		$images = array();
		foreach ( $source->Image as $image )
		{
			$images[] = new Model_Image( array(
				'_mapper' => $this->getImageMapper(),
				'id' => $image->id
			) );
		}
		
		$destination->fromArray( array(
			'id' => $source->id,
			'barcode' => $source->barcode,
			'stock' => $source->stock,
			'cldr' => $source->ProductL10n[0]->cldr,
			'title' => $source->ProductL10n[0]->title,
			'summary' => $source->ProductL10n[0]->summary,
			'images' => $images
		) );
		
		return $destination;
	}
	
	
	public function _find( Model_Product $product )
	{
		$id = $product->id;
		$cldr = $product->cldr;
		
		$results = $this->_query()
			->where( 'p.id = ? AND l.cldr = ?', array( $id, $cldr ) )
			->execute();
		
		return $this->_mapCollection( $results );
	}
}

/**
 * Product Mapper
 */
class Mapper_Product extends Dk_Model_Mapper
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
	protected function _find( Model_Product $product )
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
			'barcode' => $dao->barcode,
			'stock' => $dao->stock
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
				'id' => $daoImage->image_id
			) );
		}
		
		$product->images = $images;
	}
}

/**
 * Image Mapper
 */
class Mapper_Image extends Dk_Model_Mapper
{
	protected function _find( Model_Image $image )
	{
		$this->_mapImage( $image, $this->_loadImage( $image ) );
		return $image;
	}
	
	/**
	 * Map Image
	 *
	 * Moves data from the Image DAO into the Image DM
	 *
	 * @param Model_Image $image Domain Model
	 * @param Dao_Image   $dao   Data Access Object
	 */
	protected function _mapImage( Model_Image $image, Dao_Image $dao )
	{
		$image->fromArray( array(
			'id' => $dao->id,
			'src' => $dao->src
		) );
	}
	
	/**
	 * Load Image
	 *
	 * Fetches the Image DAO from the database using details contained
	 * within the domain model
	 *
	 * @param  Model_Image $image Domain Model
	 * @return Dao_Image   $dao   Data Access Object
	 */
	protected function _loadImage( Model_Image $image )
	{
		if ( !$image->id )
		{
			throw new Exception( 'Need ID' );
		}
		
		return Dao::gimmieImage( $image->id );
	}
}

