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
