<?php if(!defined('APPLICATION')) die();



$this->AddCssFile('videochat.css', 'plugins/VChat');?>


<h1><?php echo T('VChat'); ?></h1>


<body>
<div id="Content">
<center>
<script type="text/javascript">var tinychat = { room: "VideoChat", colorbk: "0x000000", join: "auto", api: "list"};</script>
<script src="http://tinychat.com/js/embed.js"></script>
<div id="client"><a href="http://tinychat.com">video chat</a> provided by Tinychat</div></center>
<div id="Panel"></div>
<div id="Foot"></div>
</body>
</html>