<?php
/*--------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
    __      __       _     _____                 _                                  _     __  __      _   _               _
    \ \    / /      | |   |  __ \               | |                                | |   |  \/  |    | | | |             | |
     \ \  / /_ _ ___| |_  | |  | | _____   _____| | ___  _ __  _ __ ___   ___ _ __ | |_  | \  / | ___| |_| |__   ___   __| |
      \ \/ / _` / __| __| | |  | |/ _ \ \ / / _ \ |/ _ \| '_ \| '_ ` _ \ / _ \ '_ \| __| | |\/| |/ _ \ __| '_ \ / _ \ / _` |
       \  / (_| \__ \ |_  | |__| |  __/\ V /  __/ | (_) | |_) | | | | | |  __/ | | | |_  | |  | |  __/ |_| | | | (_) | (_| |
        \/ \__,_|___/\__| |_____/ \___| \_/ \___|_|\___/| .__/|_| |_| |_|\___|_| |_|\__| |_|  |_|\___|\__|_| |_|\___/ \__,_|
                                                        | |                                                                 
                                                        |_| 				
/-------------------------------------------------------------------------------------------------------------------------------/

	@version		@update number 29 of this MVC
	@build			21st November, 2016
	@created		10th November, 2015
	@package		Sermon Distributor
	@subpackage		series.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Sermondistributor Model for Series
 */
class SermondistributorModelSeries extends JModelList
{
	/**
	 * Model user data.
	 *
	 * @var        strings
	 */
	protected $user;
	protected $userId;
	protected $guest;
	protected $groups;
	protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$this->user		= JFactory::getUser();
		$this->userId		= $this->user->get('id');
		$this->guest		= $this->user->get('guest');
                $this->groups		= $this->user->get('groups');
                $this->authorisedGroups	= $this->user->getAuthorisedGroups();
		$this->levels		= $this->user->getAuthorisedViewLevels();
		$this->app		= JFactory::getApplication();
		$this->input		= $this->app->input;
		$this->initSet		= true; 
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_sermon as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.link_type','a.short_description','a.icon','a.preacher','a.series','a.catid','a.description','a.source','a.build','a.manual_files','a.local_files','a.url','a.auto_sermons','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','name','alias','link_type','short_description','icon','preacher','series','catid','description','source','build','manual_files','local_files','url','auto_sermons','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));

		// Get from #__sermondistributor_preacher as c
		$query->select($db->quoteName(
			array('c.name','c.alias'),
			array('preacher_name','preacher_alias')));
		$query->join('LEFT', ($db->quoteName('#__sermondistributor_preacher', 'c')) . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('c.id') . ')');

		// Get from #__sermondistributor_series as d
		$query->select($db->quoteName(
			array('d.name','d.alias'),
			array('series_name','series_alias')));
		$query->join('LEFT', ($db->quoteName('#__sermondistributor_series', 'd')) . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('d.id') . ')');

		// Get from #__categories as b
		$query->select($db->quoteName(
			array('b.title','b.alias'),
			array('category','category_alias')));
		$query->join('LEFT', ($db->quoteName('#__categories', 'b')) . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id') . ')');
		$query->where('a.access IN (' . implode(',', $this->levels) . ')');
		// Check if JRequest::getInt('id') is a string or numeric value.
		$checkValue = JRequest::getInt('id');
		if (isset($checkValue) && SermondistributorHelper::checkString($checkValue))
		{
			$query->where('a.series = ' . $db->quote($checkValue));
		}
		elseif (is_numeric($checkValue))
		{
			$query->where('a.series = ' . $checkValue);
		}
		else
		{
			return false;
		}
		// Get where a.published is 1
		$query->where('a.published = 1');
		$query->order('a.ordering ASC');

		// return the query object
		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$user = JFactory::getUser();
		// check if this user has permission to access item
		if (!$user->authorise('site.series.access', 'com_sermondistributor'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_SERMONDISTRIBUTOR_NOT_AUTHORISED_TO_VIEW_SERIES'), 'error');
			// redirect away to the default view if no access allowed.
			$app->redirect(JRoute::_('index.php?option=com_sermondistributor&view=preachers'));
			return false;
		}  
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_sermondistributor', true);

		// Convert the parameter fields into objects.
		if (SermondistributorHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id.':'.$item->alias : $item->id;
				if (SermondistributorHelper::checkJson($item->local_files))
				{
					// Decode local_files
					$item->local_files = json_decode($item->local_files, true);
				}
				if (SermondistributorHelper::checkJson($item->manual_files))
				{
					// Decode manual_files
					$item->manual_files = json_decode($item->manual_files, true);
				}
				// Make sure the content prepare plugins fire on description.
				$item->description = JHtml::_('content.prepare',$item->description);
				// Checking if description has uikit components that must be loaded.
				$this->uikitComp = SermondistributorHelper::getUikitComp($item->description,$this->uikitComp);
				// set idSermonStatisticE to the $item object.
				$item->idSermonStatisticE = $this->getIdSermonStatisticEfeb_E($item->id);
			}
		} 


		// do a quick build of all the sermon links
		if (isset($items) && $items)
		{
			$pastDate = strtotime('-1 week');
			foreach ($items as $nr => &$item)
			{
				$item->isNew = false;
				// check if sermon is new
				$createdTime = strtotime($item->created);
				if ($pastDate < $createdTime)
				{
					$item->isNew = true;
				}
				$item->statisticTotal = 0;
				if (isset($item->auto_sermons) && SermondistributorHelper::checkString($item->auto_sermons))
				{
					// Decode the auto files
					$item->auto_sermons = json_decode($item->auto_sermons, true);
				}
				// set statistic per filename if found
				if (isset($item->idSermonStatisticE) && SermondistributorHelper::checkArray($item->idSermonStatisticE))
				{
					foreach ($item->idSermonStatisticE as $statistic)
					{
						$item->statistic[$statistic->filename] = $statistic->counter;
					}
					// set the total downloads for this sermon
					$item->statisticTotal = array_sum($item->statistic);
				}
				unset($item->idSermonStatisticE);
				// set preacher slug
				$item->preacher_slug = (isset($item->preacher_alias)) ? $item->preacher.':'.$item->preacher_alias : $item->preacher;
				// build category slug
				$item->category_slug = (isset($item->category_alias)) ? $item->catid.':'.$item->category_alias : $item->catid;
				// build needed links
				$item->link = JRoute::_(SermondistributorHelperRoute::getSermonRoute($item->slug));
				$item->preacher_link = JRoute::_(SermondistributorHelperRoute::getPreacherRoute($item->preacher_slug));
				$item->category_link = JRoute::_(SermondistributorHelperRoute::getCategoryRoute($item->category_slug));
				// set view key
				$item->viewKey = 'series';
				SermondistributorHelper::getDownloadLinks($item);
			}
		}

		// return items
		return $items;
	} 

	/**
	* Method to get an array of Statistic Objects.
	*
	* @return mixed  An array of Statistic Objects on success, false on failure.
	*
	*/
	public function getIdSermonStatisticEfeb_E($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_statistic as e
		$query->select($db->quoteName(
			array('e.filename','e.sermon','e.preacher','e.series','e.counter'),
			array('filename','sermon','preacher','series','counter')));
		$query->from($db->quoteName('#__sermondistributor_statistic', 'e'));
		$query->where('e.sermon = ' . $db->quote($id));

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			return $db->loadObjectList();
		}
		return false;
	}


	/**
	* Custom Method
	*
	* @return mixed  item data object on success, false on failure.
	*
	*/
	public function getSeries()
	{

		if (!isset($this->initSet) || !$this->initSet)
		{
			$this->user		= JFactory::getUser();
			$this->userId		= $this->user->get('id');
			$this->guest		= $this->user->get('guest');
			$this->groups		= $this->user->get('groups');
			$this->authorisedGroups	= $this->user->getAuthorisedGroups();
			$this->levels		= $this->user->getAuthorisedViewLevels();
			$this->initSet		= true;
		}
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_series as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.description','a.icon','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering','a.metadesc','a.metakey','a.metadata'),
			array('id','asset_id','name','alias','description','icon','published','created_by','modified_by','created','modified','version','hits','ordering','metadesc','metakey','metadata')));
		$query->from($db->quoteName('#__sermondistributor_series', 'a'));
		$query->where('a.access IN (' . implode(',', $this->levels) . ')');
		// Check if JRequest::getInt('id') is a string or numeric value.
		$checkValue = JRequest::getInt('id');
		if (isset($checkValue) && SermondistributorHelper::checkString($checkValue))
		{
			$query->where('a.id = ' . $db->quote($checkValue));
		}
		elseif (is_numeric($checkValue))
		{
			$query->where('a.id = ' . $checkValue);
		}
		else
		{
			return false;
		}
		// Get where a.published is 1
		$query->where('a.published = 1');
		$query->order('a.ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a stdClass object.
		$data = $db->loadObject();

		if (empty($data))
		{
			return false;
		}
		// Make sure the content prepare plugins fire on description.
		$data->description = JHtml::_('content.prepare',$data->description);
		// Checking if description has uikit components that must be loaded.
		$this->uikitComp = SermondistributorHelper::getUikitComp($data->description,$this->uikitComp);

		// return data object.
		return $data;
	}


	/**
	* Custom Method
	*
	* @return mixed  An array of objects on success, false on failure.
	*
	*/
	public function getNumberDownloads()
	{

		if (!isset($this->initSet) || !$this->initSet)
		{
			$this->user		= JFactory::getUser();
			$this->userId		= $this->user->get('id');
			$this->guest		= $this->user->get('guest');
			$this->groups		= $this->user->get('groups');
			$this->authorisedGroups	= $this->user->getAuthorisedGroups();
			$this->levels		= $this->user->getAuthorisedViewLevels();
			$this->initSet		= true;
		}

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_sermondistributor', true);
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_statistic as a
		$query->select($db->quoteName(
			array('a.id','a.counter'),
			array('id','counter')));
		$query->from($db->quoteName('#__sermondistributor_statistic', 'a'));
		// Check if JRequest::getInt('id') is a string or numeric value.
		$checkValue = JRequest::getInt('id');
		if (isset($checkValue) && SermondistributorHelper::checkString($checkValue))
		{
			$query->where('a.series = ' . $db->quote($checkValue));
		}
		elseif (is_numeric($checkValue))
		{
			$query->where('a.series = ' . $checkValue);
		}
		else
		{
			return false;
		}
		// Get where a.published is 1
		$query->where('a.published = 1');
		$query->order('a.ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$items = $db->loadObjectList();

		if (empty($items))
		{
			return false;
		}

		// Convert the parameter fields into objects.
		if (SermondistributorHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id.':'.$item->alias : $item->id;
			}
		}
		// return items
		return $items;
	}


	/**
	* Custom Method
	*
	* @return mixed  An array of objects on success, false on failure.
	*
	*/
	public function getNumberSermons()
	{

		if (!isset($this->initSet) || !$this->initSet)
		{
			$this->user		= JFactory::getUser();
			$this->userId		= $this->user->get('id');
			$this->guest		= $this->user->get('guest');
			$this->groups		= $this->user->get('groups');
			$this->authorisedGroups	= $this->user->getAuthorisedGroups();
			$this->levels		= $this->user->getAuthorisedViewLevels();
			$this->initSet		= true;
		}

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_sermondistributor', true);
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_sermon as a
		$query->select($db->quoteName(
			array('a.id','a.alias','a.series'),
			array('id','alias','series')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));
		// Check if JRequest::getInt('id') is a string or numeric value.
		$checkValue = JRequest::getInt('id');
		if (isset($checkValue) && SermondistributorHelper::checkString($checkValue))
		{
			$query->where('a.series = ' . $db->quote($checkValue));
		}
		elseif (is_numeric($checkValue))
		{
			$query->where('a.series = ' . $checkValue);
		}
		else
		{
			return false;
		}
		$query->where('a.access IN (' . implode(',', $this->levels) . ')');
		// Get where a.published is 1
		$query->where('a.published = 1');
		$query->order('a.ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$items = $db->loadObjectList();

		if (empty($items))
		{
			return false;
		}

		// Convert the parameter fields into objects.
		if (SermondistributorHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id.':'.$item->alias : $item->id;
			}
		}
		// return items
		return $items;
	}


	/**
	* Get the uikit needed components
	*
	* @return mixed  An array of objects on success.
	*
	*/
	public function getUikitComp()
	{
		if (isset($this->uikitComp) && SermondistributorHelper::checkArray($this->uikitComp))
		{
			return $this->uikitComp;
		}
		return false;
	}  
}
