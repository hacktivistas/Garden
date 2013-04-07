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
            <!--<div class="SiteSearch">{searchbox}</div>-->
            <ul class="SiteMenu">
               {dashboard_link}
               {discussions_link}
               {activity_link}
               {inbox_link}
               {custom_menu}
               {profile_link}
               <!-- {signinout_link}  -->
            </ul>
        </div>
                     <!-- botón escribir mensaje -->
                <div class="centrardiscusion">{$Assets.Panel.NewDiscussionModule}</div>

      </div>
      <div id="Body">

<!-- iframe con el mapa oculto -->
<iframe id="iframeforomap" src="http://bankia.mepone.net/map/foros.html" style="display:none;margin:0;padding:0;position:relative;top:0;left:0;width:100%;height:500px;z-index:1000;" width="100%" height="500"></iframe>


         <div class="Row">
            <div class="BreadcrumbsWrapper">{breadcrumbs}</div>
            <div class="Column PanelColumn" id="Panel">
               {module name="MeModule"}
		{if $User.SignedIn}
			<div><a class="Button Primary BigButton" href="">1. Viaja a tu foro</a></div> 
		{else}
			<div><a rel="nofollow" class="Button Primary BigButton SignInPopup" href="http://www.toqueabankia.net/toque.php">0. Regístrese</a></div>
			<div><a rel="nofollow" class="Button Primary BigButton SignInPopup" href="/entry/signin?Target=discussions">1. Acceder</a></div>
		{/if}

		<div><a class="Button Primary BigButton" id="boton_iframe">2. Mapa sucursales</a></div>

		<!-- SELECT PROVINCIAS -->
		<select id="forosprovinciales" style="width:100%">
			<option value="/categories/alava">Álava</option>
			<option value="/categories/albacete">Albacete</option>
			<option value="/categories/alicante">Alicante</option>
			<option value="/categories/almeria">Almería</option>
			<option value="/categories/asturias">Asturias</option>
			<option value="/categories/avila">Ávila</option>
			<option value="/categories/badajoz">Badajoz</option>
			<option value="/categories/barcelona">Barcelona</option>
			<option value="/categories/burgos">Burgos</option>
			<option value="/categories/caceres">Cáceres</option>
			<option value="/categories/cadiz">Cádiz</option>
			<option value="/categories/cantabria">Cantabria</option>
			<option value="/categories/castellon">Castellón</option>
			<option value="/categories/ceuta">Ceuta</option>
			<option value="/categories/ciudad-real">Ciudad Real</option>
			<option value="/categories/cordoba">Córdoba</option>
			<option value="/categories/coruna">Coruña</option>
			<option value="/categories/cuenca">Cuenca</option>
			<option value="/categories/florida">Florida</option>
			<option value="/categories/girona">Girona</option>
			<option value="/categories/granada">Granada</option>
			<option value="/categories/greater-london">Greater London</option>
			<option value="/categories/guadalajara">Guadalajara</option>
			<option value="/categories/guipuzcoa">Guipuzcoa</option>
			<option value="/categories/huelva">Huelva</option>
			<option value="/categories/huesca">Huesca</option>
			<option value="/categories/illes-balears">Illes Balears</option>
			<option value="/categories/jaen">Jaén</option>
			<option value="/categories/la-rioja">La Rioja</option>
			<option value="/categories/las-palmas">Las Palmas</option>
			<option value="/categories/leon">León</option>
			<option value="/categories/lleida">Lleida</option>
			<option value="/categories/lugo">Lugo</option>
			<option value="/categories/madrid">Madrid</option>
			<option value="/categories/malaga">Málaga</option>
			<option value="/categories/milano">Milano</option>
			<option value="/categories/munich">Munich</option>
			<option value="/categories/murcia">Murcia</option>
			<option value="/categories/navarra">Navarra</option>
			<option value="/categories/ourense">Ourense</option>
			<option value="/categories/palencia">Palencia</option>
			<option value="/categories/paris">Paris</option>
			<option value="/categories/pirate-bay">Pirate Bay</option>
			<option value="/categories/pontevedra">Pontevedra</option>
			<option value="/categories/portugal">Portugal</option>
			<option value="/categories/sc-tenerife">S.C. Tenerife</option>
			<option value="/categories/salamanca">Salamanca</option>
			<option value="/categories/segovia">Segovia</option>
			<option value="/categories/sevilla">Sevilla</option>
			<option value="/categories/shanghai">Shanghai</option>
			<option value="/categories/soria">Soria</option>
			<option value="/categories/tarragona">Tarragona</option>
			<option value="/categories/teruel">Teruel</option>
			<option value="/categories/toledo">Toledo</option>
			<option value="/categories/valencia">Valencia</option>
			<option value="/categories/valladolid">Valladolid</option>
			<option value="/categories/vienna">Vienna</option>
			<option value="/categories/vizcaya">Vizcaya</option>
			<option value="/categories/warsaw">Warsaw</option>
			<option value="/categories/zamora">Zamora</option>
			<option value="/categories/zaragoza">Zaragoza</option>
		</select>

		<!-- FIN SELECT PROVINCIAS -->

		

               <!--{asset name="Panel"}-->
	    </div>
            <div class="Column ContentColumn" id="Content">
			{asset name="Content"}
		</div>
         </div>
      </div>
      <div id="Foot">
         <div class="Row">
<!--{$Assets.Panel.NewDiscussionModule}-->
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

	$('#forosprovinciales').bind('change', function() {
		var url = $(this).val();
		if (url) { 
			window.location = url;
		}
		return false;
	});


});
</script>
{/literal}

</body>
</html>
