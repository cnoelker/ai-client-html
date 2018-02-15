<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Account\Watch;


/**
 * Default implementation of account watch HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/account/watch/standard/subparts
	 * List of HTML sub-clients rendered within the account watch section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartPath = 'client/html/account/watch/standard/subparts';
	private $subPartNames = [];
	private $view;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string HTML code
	 */
	public function getBody( $uid = '' )
	{
		$context = $this->getContext();
		$view = $this->getView();

		try
		{
			if( !isset( $this->view ) ) {
				$view = $this->view = $this->getObject()->addData( $view );
			}

			$html = '';
			foreach( $this->getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getBody( $uid );
			}
			$view->watchBody = $html;
		}
		catch( \Aimeos\Client\Html\Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'client', $e->getMessage() ) );
			$view->watchErrorList = $view->get( 'watchErrorList', [] ) + $error;
		}
		catch( \Aimeos\Controller\Frontend\Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->watchErrorList = $view->get( 'watchErrorList', [] ) + $error;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->watchErrorList = $view->get( 'watchErrorList', [] ) + $error;
		}
		catch( \Exception $e )
		{
			$error = array( $context->getI18n()->dt( 'client', 'A non-recoverable error occured' ) );
			$view->watchErrorList = $view->get( 'watchErrorList', [] ) + $error;
			$this->logException( $e );
		}

		/** client/html/account/watch/standard/template-body
		 * Relative path to the HTML body template of the account watch client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in client/html/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "standard" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "standard"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/account/watch/standard/template-header
		 */
		$tplconf = 'client/html/account/watch/standard/template-body';
		$default = 'account/watch/body-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( $uid = '' )
	{
		$view = $this->getView();

		try
		{
			if( !isset( $this->view ) ) {
				$view = $this->view = $this->getObject()->addData( $view );
			}

			$html = '';
			foreach( $this->getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getHeader( $uid );
			}
			$view->watchHeader = $html;

			/** client/html/account/watch/standard/template-header
			 * Relative path to the HTML header template of the account watch client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the HTML code that is inserted into the HTML page header
			 * of the rendered page in the frontend. The configuration string is the
			 * path to the template file relative to the templates directory (usually
			 * in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page head
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/account/watch/standard/template-body
			 */
			$tplconf = 'client/html/account/watch/standard/template-header';
			$default = 'account/watch/header-standard.php';

			return $view->render( $view->config( $tplconf, $default ) );
		}
		catch( \Exception $e )
		{
			$this->logException( $e );
		}
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** client/html/account/watch/decorators/excludes
		 * Excludes decorators added by the "common" option from the account watch html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/account/watch/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/watch/decorators/global
		 * @see client/html/account/watch/decorators/local
		 */

		/** client/html/account/watch/decorators/global
		 * Adds a list of globally available decorators only to the account watch html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/account/watch/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/watch/decorators/excludes
		 * @see client/html/account/watch/decorators/local
		 */

		/** client/html/account/watch/decorators/local
		 * Adds a list of local decorators only to the account watch html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Account\Decorator\*") around the html client.
		 *
		 *  client/html/account/watch/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Account\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/watch/decorators/excludes
		 * @see client/html/account/watch/decorators/global
		 */

		return $this->createSubClient( 'account/watch/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$view = $this->getView();
		$context = $this->getContext();
		$userId = $context->getUserId();
		$ids = (array) $view->param( 'wat_id', [] );

		if( $userId != null && !empty( $ids ) )
		{
			$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'customer/lists/type' );
			$typeId = $typeManager->findItem( 'watch', [], 'product' )->getId();

			$manager = \Aimeos\MShop\Factory::createManager( $context, 'customer/lists' );
			$items = $this->getListItems( $manager, $ids, $typeId, $userId );

			switch( $view->param( 'wat_action' ) )
			{
				case 'add':

					/** client/html/account/watch/standard/maxitems
					 * Maximum number of products that can be watched in parallel
					 *
					 * This option limits the number of products that can be watched
					 * after the users added the products to their watch list.
					 * It must be a positive integer value greater than 0.
					 *
					 * Note: It's recommended to set this value not too high as this
					 * leads to a high memory consumption when the e-mails are generated
					 * to notify the customers. The memory used will up to 100*maxitems
					 * of the footprint of one product item including the associated
					 * texts, prices and media.
					 *
					 * @param integer Number of products
					 * @since 2014.09
					 * @category User
					 * @category Developer
					 */
					$max = $context->getConfig()->get( 'client/html/account/watch/standard/maxitems', 100 );
					$cnt = count( $ids );

					if( $this->checkLimit( $manager, $typeId, $userId, $max, $cnt ) === false )
					{
						$error = sprintf( $context->getI18n()->dt( 'client', 'You can only watch up to %1$s products' ), $max );
						$view->watchErrorList = $view->get( 'watchErrorList', [] ) + array( $error );
						break;
					}

					$this->addItems( $manager, $items, $ids, $typeId, $userId );
					break;

				case 'edit':

					$config = array(
						'timeframe' => $view->param( 'wat_timeframe', 7 ),
						'pricevalue' => $view->param( 'wat_pricevalue', '0.00' ),
						'price' => $view->param( 'wat_price', 0 ),
						'stock' => $view->param( 'wat_stock', 0 ),
						'currency' => $context->getLocale()->getCurrencyId(),
					);
					$this->editItems( $manager, $items, $ids, $config );
					break;

				case 'delete':

					$this->deleteItems( $manager, $items, $ids );
					break;
			}
		}

		parent::process();
	}


	/**
	 * Tests if the maximum number of entries per user is already reached
	 *
	 * @param \Aimeos\MShop\Common\Manager\Iface $manager Customer list manager
	 * @param string $typeId List type ID of the referenced items
	 * @param string $userId Unique user ID
	 * @param integer $max Maximum number of items that are allowed
	 * @param integer $cnt Number of items that should be added
	 * @return boolean True if items can be added, false if not
	 */
	protected function checkLimit( \Aimeos\MShop\Common\Manager\Iface $manager, $typeId, $userId, $max, $cnt )
	{
		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'customer.lists.parentid', $userId ),
			$search->compare( '==', 'customer.lists.typeid', $typeId ),
			$search->compare( '==', 'customer.lists.domain', 'product' ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSlice( 0, 0 );

		$total = 0;
		$manager->searchItems( $search, [], $total );

		if( $total + $cnt > $max ) {
			return false;
		}

		return true;
	}


	/**
	 * Adds one or more list items to the given user
	 *
	 * @param \Aimeos\MShop\Common\Manager\Iface $manager Customer list manager
	 * @param array $listItems Associative list of the reference IDs as keys and the list items as values
	 * @param array $ids List of referenced IDs
	 * @param string $typeId List type ID of the referenced items
	 * @param string $userId Unique user ID
	 */
	protected function addItems( \Aimeos\MShop\Common\Manager\Iface $manager, array $listItems, array $ids, $typeId, $userId )
	{
		$item = $manager->createItem();
		$item->setParentId( $userId );
		$item->setTypeId( $typeId );
		$item->setDomain( 'product' );
		$item->setStatus( 1 );

		foreach( $ids as $id )
		{
			if( !isset( $listItems[$id] ) )
			{
				$item->setId( null );
				$item->setRefId( $id );

				$item = $manager->saveItem( $item );
				$manager->moveItem( $item->getId() );
			}
		}
	}


	/**
	 * Removes the list items for the given reference IDs
	 *
	 * @param \Aimeos\MShop\Common\Manager\Iface $manager Customer list manager
	 * @param array $listItems Associative list of the reference IDs as keys and the list items as values
	 * @param array $ids List of referenced IDs
	 */
	protected function deleteItems( \Aimeos\MShop\Common\Manager\Iface $manager, array $listItems, array $ids )
	{
		$listIds = [];

		foreach( $ids as $id )
		{
			if( isset( $listItems[$id] ) ) {
				$listIds[] = $listItems[$id]->getId();
			}
		}

		$manager->deleteItems( $listIds );
	}


	/**
	 * Updates the list items for the given reference IDs
	 *
	 * @param \Aimeos\MShop\Common\Manager\Iface $manager Customer list manager
	 * @param array $listItems Associative list of the reference IDs as keys and the list items as values
	 * @param array $ids List of referenced IDs
	 * @param array $config Configuration settins with "timeframe", "pricevalue", "price", "stock" and "currency"
	 */
	protected function editItems( \Aimeos\MShop\Common\Manager\Iface $manager, array $listItems, array $ids, array $config )
	{
		foreach( $ids as $id )
		{
			if( isset( $listItems[$id] ) )
			{
				$item = $listItems[$id];
				$time = time() + ( $config['timeframe'] + 1 ) * 86400;

				$item->setDateEnd( date( 'Y-m-d 00:00:00', $time ) );
				$item->setConfig( $config );

				$manager->saveItem( $item );
			}
		}
	}


	/**
	 * Returns the list items associated to the given user ID
	 *
	 * @param \Aimeos\MShop\Common\Manager\Iface $manager Customer list manager
	 * @param array $refIds IDs of the referenced items
	 * @param string $typeId List type ID of the referenced items
	 * @param string $userId Unique user ID
	 * @return array Associative list of the reference IDs as keys and the list items as values
	 */
	protected function getListItems( \Aimeos\MShop\Common\Manager\Iface $manager, array $refIds, $typeId, $userId )
	{
		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'customer.lists.parentid', $userId ),
			$search->compare( '==', 'customer.lists.refid', $refIds ),
			$search->compare( '==', 'customer.lists.domain', 'product' ),
			$search->compare( '==', 'customer.lists.typeid', $typeId ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );

		$items = [];
		foreach( $manager->searchItems( $search ) as $item ) {
			$items[$item->getRefId()] = $item;
		}

		return $items;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Returns the sanitized page from the parameters for the product list.
	 *
	 * @param \Aimeos\MW\View\Iface $view View instance with helper for retrieving the required parameters
	 * @return integer Page number starting from 1
	 */
	protected function getProductListPage( \Aimeos\MW\View\Iface $view )
	{
		$page = (int) $view->param( 'wat_page', 1 );
		return ( $page < 1 ? 1 : $page );
	}


	/**
	 * Returns the sanitized page size from the parameters for the product list.
	 *
	 * @param \Aimeos\MW\View\Iface $view View instance with helper for retrieving the required parameters
	 * @return integer Page size
	 */
	protected function getProductListSize( \Aimeos\MW\View\Iface $view )
	{
		/** client/html/account/watch/size
		 * The number of products shown in a list page for watch products
		 *
		 * Limits the number of products that is shown in the list pages to the
		 * given value. If more products are available, the products are split
		 * into bunches which will be shown on their own list page. The user is
		 * able to move to the next page (or previous one if it's not the first)
		 * to display the next (or previous) products.
		 *
		 * The value must be an integer number from 1 to 100. Negative values as
		 * well as values above 100 are not allowed. The value can be overwritten
		 * per request if the "l_size" parameter is part of the URL.
		 *
		 * @param integer Number of products
		 * @since 2014.09
		 * @category User
		 * @category Developer
		 * @see client/html/catalog/lists/size
		 */
		$defaultSize = $this->getContext()->getConfig()->get( 'client/html/account/watch/size', 48 );

		$size = (int) $view->param( 'watch-size', $defaultSize );
		return ( $size < 1 || $size > 100 ? $defaultSize : $size );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	public function addData( \Aimeos\MW\View\Iface $view, array &$tags = [], &$expire = null )
	{
		$total = 0;
		$productIds = [];
		$context = $this->getContext();

		$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'customer/lists/type' );
		$typeItem = $typeManager->findItem( 'watch', [], 'product' );

		$size = $this->getProductListSize( $view );
		$current = $this->getProductListPage( $view );
		$last = ( $total != 0 ? ceil( $total / $size ) : 1 );


		$manager = \Aimeos\MShop\Factory::createManager( $context, 'customer/lists' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'customer.lists.parentid', $context->getUserId() ),
			$search->compare( '==', 'customer.lists.typeid', $typeItem->getId() ),
			$search->compare( '==', 'customer.lists.domain', 'product' ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSortations( array( $search->sort( '-', 'customer.lists.position' ) ) );
		$search->setSlice( ( $current - 1 ) * $size, $size );

		$view->watchListItems = $manager->searchItems( $search, [], $total );


		/** client/html/account/watch/domains
		 * A list of domain names whose items should be available in the account watch view template
		 *
		 * The templates rendering product details usually add the images,
		 * prices and texts associated to the product item. If you want to
		 * display additional or less content, you can configure your own
		 * list of domains (attribute, media, price, product, text, etc. are
		 * domains) whose items are fetched from the storage. Please keep
		 * in mind that the more domains you add to the configuration, the
		 * more time is required for fetching the content!
		 *
		 * @param array List of domain names
		 * @since 2014.09
		 * @category Developer
		 * @see client/html/catalog/domains
		 */
		$default = array( 'text', 'price', 'media' );
		$domains = $context->getConfig()->get( 'client/html/account/watch/domains', $default );

		foreach( $view->watchListItems as $listItem ) {
			$productIds[] = $listItem->getRefId();
		}

		$controller = \Aimeos\Controller\Frontend\Factory::createController( $context, 'product' );

		$view->watchProductItems = $controller->getItems( $productIds, $domains );
		$view->watchPageFirst = 1;
		$view->watchPagePrev = ( $current > 1 ? $current - 1 : 1 );
		$view->watchPageNext = ( $current < $last ? $current + 1 : $last );
		$view->watchPageLast = $last;
		$view->watchPageCurr = $current;

		return parent::addData( $view, $tags, $expire );
	}
}