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

	@version		1.3.0
	@build			20th February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_documents.php
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
 * Help_documents Model
 */
class SermondistributorModelHelp_documents extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
        {
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.title','title',
				'a.type','type',
				'a.location','location',
				'a.admin_view','admin_view',
				'a.site_view','site_view'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$title = $this->getUserStateFromRequest($this->context . '.filter.title', 'filter_title');
		$this->setState('filter.title', $title);

		$type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type');
		$this->setState('filter.type', $type);

		$location = $this->getUserStateFromRequest($this->context . '.filter.location', 'filter_location');
		$this->setState('filter.location', $location);

		$admin_view = $this->getUserStateFromRequest($this->context . '.filter.admin_view', 'filter_admin_view');
		$this->setState('filter.admin_view', $admin_view);

		$site_view = $this->getUserStateFromRequest($this->context . '.filter.site_view', 'filter_site_view');
		$this->setState('filter.site_view', $site_view);
        
		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);
        
		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
        
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
        
		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{ 
		// [11248] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// [11323] set values to display correctly.
		if (SermondistributorHelper::checkArray($items))
		{
			// [11326] get user object.
			$user = JFactory::getUser();
			foreach ($items as $nr => &$item)
			{
				$access = ($user->authorise('help_document.access', 'com_sermondistributor.help_document.' . (int) $item->id) && $user->authorise('help_document.access', 'com_sermondistributor'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

				// [11394] decode groups
				$groupsArray = json_decode($item->groups, true);
				if (SermondistributorHelper::checkArray($groupsArray))
				{
					$groupsNames = '';
					$counter = 0;
					foreach ($groupsArray as $groups)
					{
						if ($counter == 0)
						{
							$groupsNames .= SermondistributorHelper::getGroupName($groups);
						}
						else
						{
							$groupsNames .= ', '.SermondistributorHelper::getGroupName($groups);
						}
						$counter++;
					}
					$item->groups = $groupsNames;
				}
			}
		} 

		// [11589] set selection value to a translatable value
		if (SermondistributorHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [11596] convert type
				$item->type = $this->selectionTranslation($item->type, 'type');
				// [11596] convert location
				$item->location = $this->selectionTranslation($item->location, 'location');
			}
		}

        
		// return items
		return $items;
	}

	/**
	* Method to convert selection values to translatable string.
	*
	* @return translatable string
	*/
	public function selectionTranslation($value,$name)
	{
		// [11622] Array of type language strings
		if ($name == 'type')
		{
			$typeArray = array(
				0 => 'COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SELECT_AN_OPTION',
				1 => 'COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_JOOMLA_ARTICLE',
				2 => 'COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TEXT',
				3 => 'COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_URL'
			);
			// [11653] Now check if value is found in this array
			if (isset($typeArray[$value]) && SermondistributorHelper::checkString($typeArray[$value]))
			{
				return $typeArray[$value];
			}
		}
		// [11622] Array of location language strings
		if ($name == 'location')
		{
			$locationArray = array(
				1 => 'COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_ADMIN',
				2 => 'COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SITE'
			);
			// [11653] Now check if value is found in this array
			if (isset($locationArray[$value]) && SermondistributorHelper::checkString($locationArray[$value]))
			{
				return $locationArray[$value];
			}
		}
		return $value;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// [8085] Get the user object.
		$user = JFactory::getUser();
		// [8087] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [8090] Select some fields
		$query->select('a.*');

		// [8097] From the sermondistributor_item table
		$query->from($db->quoteName('#__sermondistributor_help_document', 'a'));

		// [8111] Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// [8123] Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// [8126] Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}
		// [8131] Implement View Level Access
		if (!$user->authorise('core.options', 'com_sermondistributor'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		// [8208] Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.title LIKE '.$search.' OR a.type LIKE '.$search.' OR a.location LIKE '.$search.' OR a.admin_view LIKE '.$search.' OR a.site_view LIKE '.$search.')');
			}
		}

		// [8451] Filter by Type.
		if ($type = $this->getState('filter.type'))
		{
			$query->where('a.type = ' . $db->quote($db->escape($type, true)));
		}
		// [8451] Filter by Location.
		if ($location = $this->getState('filter.location'))
		{
			$query->where('a.location = ' . $db->quote($db->escape($location, true)));
		}
		// [8451] Filter by Admin_view.
		if ($admin_view = $this->getState('filter.admin_view'))
		{
			$query->where('a.admin_view = ' . $db->quote($db->escape($admin_view, true)));
		}
		// [8451] Filter by Site_view.
		if ($site_view = $this->getState('filter.site_view'))
		{
			$query->where('a.site_view = ' . $db->quote($db->escape($site_view, true)));
		}

		// [8167] Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	* Method to get list export data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExportData($pks)
	{
		// [7875] setup the query
		if (SermondistributorHelper::checkArray($pks))
		{
			// [7878] Get the user object.
			$user = JFactory::getUser();
			// [7880] Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// [7883] Select some fields
			$query->select('a.*');

			// [7885] From the sermondistributor_help_document table
			$query->from($db->quoteName('#__sermondistributor_help_document', 'a'));
			$query->where('a.id IN (' . implode(',',$pks) . ')');
			// [7895] Implement View Level Access
			if (!$user->authorise('core.options', 'com_sermondistributor'))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}

			// [7902] Order the results by ordering
			$query->order('a.ordering  ASC');

			// [7904] Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// [11323] set values to display correctly.
				if (SermondistributorHelper::checkArray($items))
				{
					// [11326] get user object.
					$user = JFactory::getUser();
					foreach ($items as $nr => &$item)
					{
						$access = ($user->authorise('help_document.access', 'com_sermondistributor.help_document.' . (int) $item->id) && $user->authorise('help_document.access', 'com_sermondistributor'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						// [11536] unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// [11545] Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (SermondistributorHelper::checkObject($headers))
				{
					array_unshift($items,$headers);
				}
				return $items;
			}
		}
		return false;
	}

	/**
	* Method to get header.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExImPortHeaders()
	{
		// [7924] Get a db connection.
		$db = JFactory::getDbo();
		// [7926] get the columns
		$columns = $db->getTableColumns("#__sermondistributor_help_document");
		if (SermondistributorHelper::checkArray($columns))
		{
			// [7930] remove the headers you don't import/export.
			unset($columns['asset_id']);
			unset($columns['checked_out']);
			unset($columns['checked_out_time']);
			$headers = new stdClass();
			foreach ($columns as $column => $type)
			{
				$headers->{$column} = $column;
			}
			return $headers;
		}
		return false;
	} 
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 *
	 */
	protected function getStoreId($id = '')
	{
		// [10871] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.title');
		$id .= ':' . $this->getState('filter.type');
		$id .= ':' . $this->getState('filter.location');
		$id .= ':' . $this->getState('filter.admin_view');
		$id .= ':' . $this->getState('filter.site_view');

		return parent::getStoreId($id);
	}

	/**
	* Build an SQL query to checkin all items left checked out longer then a set time.
	*
	* @return  a bool
	*
	*/
	protected function checkInNow()
	{
		// [11264] Get set check in time
		$time = JComponentHelper::getParams('com_sermondistributor')->get('check_in');
		
		if ($time)
		{

			// [11269] Get a db connection.
			$db = JFactory::getDbo();
			// [11271] reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__sermondistributor_help_document'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// [11279] Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// [11281] reset query
				$query = $db->getQuery(true);

				// [11283] Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// [11288] Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// [11293] Check table
				$query->update($db->quoteName('#__sermondistributor_help_document'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
