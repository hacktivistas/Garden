<!DOCTYPE html>
<html>
<head>
  {asset name="Head"}
</head>
<body id="{$BodyID}" class="{$BodyClass}">

   <div id="Frame">
      <div class="Head" id="Head">
         <div class="Row">
            <!--<strong class="SiteTitle"><a href="{link path="/"}">{logo}</a></strong>-->
            <strong class="SiteTitle"><a href="{link path="/"}"><img src="/themes/brankia/logoforo.png" /></a></strong>
            <div class="SiteSearch">{searchbox}</div>
            <ul class="SiteMenu">
               <!-- {dashboard_link} -->
               {discussions_link}
               {activity_link}
               <!-- {inbox_link} -->
               {custom_menu}
               <!-- {profile_link}
               {signinout_link}  -->
            </ul>
         </div>
      </div>
      <div id="Body">

<!-- iframe con el mapa oculto -->
<iframe id="iframeforomap" src="/themes/brankia/foromap/index.html" style="display:none;margin:0;padding:0;position:relative;top:0;left:0;width:100%;height:500px;z-index:1000;" width="100%" height="500"></iframe>


         <div class="Row">
            <div class="BreadcrumbsWrapper">{breadcrumbs}</div>
            <div class="Column PanelColumn" id="Panel">
               {module name="MeModule"}
		<div><a class="Button Primary BigButton" href="">Viaja a tu foro</a></div> 
		<div><a class="Button Primary BigButton" id="boton_iframe">Mapa sucursales</a></div>
               {asset name="Panel"}
	    </div>
            <div class="Column ContentColumn" id="Content">
				{asset name="Content"}
			</div>
         </div>
      </div>
      <div id="Foot">
         <div class="Row">
            <a href="{vanillaurl}" class="PoweredByVanilla" title="Community Software by Vanilla Forums">Powered by Vanilla</a>
            {asset name="Foot"}
         </div>
      </div>
   </div>
   {event name="AfterBody"}

{literal}
<script type="text/javascript">
$(function() {
	$('#boton_iframe').on('click', function(e) {
		$('#iframeforomap').slideDown();
	});
});
</script>
{/literal}

</body>
</html>
