<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$db =& JFactory::getDBO();
$user =& JFactory::getUser();
$matricola = $user->get('id');

$query = "SELECT * FROM #__pf_project_members WHERE user_id=$matricola";
$db->setQuery( $query );
$db->query();        
$result = $db->loadResult();  

	
global $mainframe;
// Get an ACL object
$acl =& JFactory::getACL();
// Get the user group from the ACL
$grp = $acl->getAroGroup($user->get('id'));
      

if(($result)||($grp->name=='Progetti')){
    // include the template for display
    require(JModuleHelper::getLayoutPath('mod_presenze_progetti'));
}
?>