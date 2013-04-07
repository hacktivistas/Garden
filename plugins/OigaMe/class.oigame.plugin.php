<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2013 ToqueaBankia.net.
This is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
*/

// Define the plugin:
$PluginInfo['OigaMe'] = array(
        'Name' => 'OigaMe',
   'Description' => 'This plugin integrates Oiga.me with Vanilla.',
   'Version' => '0.1a',
   'RequiredApplications' => array('Vanilla' => '2.0.18.4'),
   'RequiredTheme' => FALSE,
   'RequiredPlugins' => FALSE,
        'MobileFriendly' => TRUE,
   'SettingsUrl' => '/dashboard/plugin/oigame',
   'HasLocale' => TRUE,
   'RegisterPermissions' => FALSE,
   'Author' => "psy",
   'AuthorEmail' => 'epsylon@riseup.net',
   'AuthorUrl' => 'mepone.net'
);

class OigaMePlugin extends Gdn_Plugin {
   
   /**
    * Add the OigaMe admin menu option.
    */
   public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
      $Menu->AddItem('Protest', T('Protest'));
      $Menu->AddLink('Protest', T('OigaMe'), 'plugin/OigaMe', 'Garden.Settings.Manage');
   }

    public function PluginController_OigaMe_Create($Sender) {

        $Session = Gdn::Session();

        if ($Sender->Menu)  {
            $Sender->Render('OigaMe', '', 'plugins/OigaMe');
        }
    }

}
