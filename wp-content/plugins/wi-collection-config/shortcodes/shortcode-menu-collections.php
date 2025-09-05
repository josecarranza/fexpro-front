<?php 

foreach($departments as $department):?>
	 
	<ul class="mega-sub-menu">
		<?php foreach($department["collections"] as $c):?>
		<li class="mega-menu-item mega-menu-item-type-custom mega-menu-item-object-custom mega-collection-item-menu mega-coll-1">
			<a href="<?=$c["link_catalog"]?>&from=landing"><?=$c["name"]?></a> 
		</li>
		<?php endforeach;?>
	</ul>
<?php endforeach;?>