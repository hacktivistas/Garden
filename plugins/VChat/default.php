<?php if(!defined('APPLICATION')) die();

$PluginInfo['VChat'] = array(
   'Name' => 'VChat',
   'Description' => "Adds a Video Chat Room Page . Based on peregrine's ExtraPage",
   'Version' => '1.0',
   'Author' => "VrijVlinder"
);

class VChatPlugin extends Gdn_Plugin {

    public function Base_Render_Before($Sender) {
        $Session = Gdn::Session();
       if ($Sender->Menu) {
           $Sender->Menu->AddLink(T('VChat'), T('VChat'), 'plugin/VChat');
         }
    }

   

    public function PluginController_VChat_Create($Sender) {
   
        $Session = Gdn::Session();

        if ($Sender->Menu)  {
            $Sender->ClearCssFiles();
            $Sender->AddCssFile('style.css');
            $Sender->AddCssFile('videochat.css', 'plugins/VChat');
            $Sender->MasterView = 'default';

            $Sender->Render('VChat', '', 'plugins/VChat');
        }
    
   
    }

    public function Setup() {
        
    }

}

