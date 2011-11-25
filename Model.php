<?php

/**
 * The Product Model
 */
class Model_Product extends Dk_Model
{
	public $_id = null;
	public $_barcode = null;
	public $_stock = null;
	
	public $_cldr = null;
	public $_title = null;
	public $_summary = null;
	
	public $_images = null;
	
	public function identity()
	{
		return 'urn:product:' . $this->_cldr . ':' . $this->_id;
	}
}

/**
 * The Image Model
 */
class Model_Image extends Dk_Model
{
	protected $_id = null;
	protected $_src = null;
}

