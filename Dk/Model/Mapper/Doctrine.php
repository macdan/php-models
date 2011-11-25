<?php

/**
 * Mapper_Doctrine
 * 
 * A Domain Model Data Mapper designed to work
 * with Doctrine ORM (1.2) as a Data Access Layer.
 */
class Dk_Model_Mapper_Doctrine extends Dk_Model_Mapper
{
	/**
	 * @var string The class name of the domain model
	 */
	protected $_domainModelClass = '';
	
	/**
	 * Query
	 *
	 * Get a base query. This contains the SELECT, 
	 * FROM and any JOINs required for this domain
	 * model. Note that I'm not retrieving the entire
	 * Image objects, just their IDs.
	 */
	protected function _query()
	{
		return Doctrine_Query::create()
			->select( 'p.*, l.*, i.id' )
			->from( 'Model_Dao_Product p' )
			->leftJoin( 'p.ProductL10n l' )
			->leftJoin( 'p.Image i' );
	}
	
	/**
	 * New Model
	 *
	 * Returns a new instance of the Domain Model
	 */
	public function newModel()
	{
		return new $this->_domainModelClass;
	}
	
	/**
	 * Map Collection
	 *
	 * Takes Doctrine_Collection of DAO objects and maps
	 * them to Domain Models using _map( $src, $dst ).
	 *
	 * @param Doctrine_Collection $collection The collection of DAOs
	 * @returns Dk_Model_Collection A collection of Domain Models.
	 */
	protected function _mapCollection( Doctrine_Collection $collection )
	{
		$models = new Dk_Model_Collection;
		foreach ( $collection as $source )
		{
			$models->append( $this->_map( $source, $this->newModel() ) );
		}
		return $models;
	}
}
