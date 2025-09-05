<?php 

foreach($departments as $department):?>
	<label for="" class="collection-list-department"><?=$department["name"]?></label>
	<ul>
		<?php foreach($department["collections"] as $c):?>
		<li><a href="<?=$c["link_catalog"]?>&from=landing"><?=$c["name"]?></a> 
			<?php if($c["pdf"]!=""):?>
				<a class="pdf" href="<?=$c["pdf"]?>" target="_blank">
					<span class="ico-export"></span>
				</a>
			<?php endif;?></li>
		<?php endforeach;?>
	</ul>
<?php endforeach;?>