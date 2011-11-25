<?php

/**
 * Mapper_DummyDao_Image
 *
 * An Image Mapper that maps from the Dummy DAO.
 */
class Mapper_DummyDao_Image extends Dk_Model_Mapper
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
