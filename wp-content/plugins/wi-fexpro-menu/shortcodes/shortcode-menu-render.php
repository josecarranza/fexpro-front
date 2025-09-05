<?php 
// echo "<pre>";
// echo json_encode($menu_json,JSON_PRETTY_PRINT);
// echo "</pre>";
?>
<div class="wi-fexpro-menu <?=$css_class?>">
	<ul class="wfm-level-0">
	<?php 
		foreach($menu_json as $lvl0): if($lvl0['disabled']) continue;
	?>
	<li class="<?=$lvl0['css']?> <?=$lvl0['hide_title']?'wfm-hide-title':''?>">
		<a href="<?=$lvl0['link']?>">
			<?php if($lvl0['image']!=null && $lvl0['image']!=''):?>
				<span class="img-normal"><img src="<?=$lvl0['image']?>" alt="" /></span>
			<?php endif;?>
			<?php if($lvl0['image_hover']!=null && $lvl0['image_hover']!=''):?>
				<span class="img-hover"><img  src="<?=$lvl0['image_hover']?>" alt="" /></span>
			<?php endif;?>
			<span class="title"><?=$lvl0['title']?></span>
		</a>
		<?php 
			if(isset($lvl0['items']) && count($lvl0['items'])>0):
		?>
			<ul class="wfm-level-1">
				<?php 
					foreach($lvl0['items'] as $lvl1):  if($lvl1['disabled']) continue;
				?>
				<li class="<?=$lvl1['css']?> <?=$lvl1['hide_title']?'wfm-hide-title':''?>">
					<a href="<?=$lvl1['link']?>">
					<?php if($lvl1['image']!=null && $lvl1['image']!=''):?>
						<span class="img-normal"><img src="<?=$lvl1['image']?>" alt="" /></span>
					<?php endif;?>
					<?php if($lvl1['image_hover']!=null && $lvl1['image_hover']!=''):?>
						<span class="img-hover"><img  src="<?=$lvl1['image_hover']?>" alt="" /></span>
					<?php endif;?>
						<span class="title"><?=$lvl1['title']?></span>
					</a>
					<?php 
						if(isset($lvl1['items']) && count($lvl1['items'])>0):
					?>
						<ul class="wfm-level-2">
							<?php 
								foreach($lvl1['items'] as $lvl2):  if($lvl2['disabled']) continue;
							?>
							<li class="<?=$lvl2['css']?> <?=$lvl2['hide_title']?'wfm-hide-title':''?>">
								<a href="<?=$lvl2['link']?>">
									<?php if($lvl2['image']!=null && $lvl2['image']!=''):?>
										<span class="img-normal"><img src="<?=$lvl2['image']?>" alt="" /></span>
									<?php endif;?>
									<?php if($lvl2['image_hover']!=null && $lvl2['image_hover']!=''):?>
										<span class="img-hover"><img  src="<?=$lvl2['image_hover']?>" alt="" /></span>
									<?php endif;?>
									<span class="title"><?=$lvl2['title']?></span>
								</a>
								<?php 
									if(isset($lvl2['items']) && count($lvl2['items'])>0):
								?>
									<ul class="wfm-level-3">
										<?php 
											foreach($lvl2['items'] as $lvl3):  if($lvl3['disabled']) continue;
										?>
										<li class="<?=$lvl3['css']?> <?=$lvl3['hide_title']?'wfm-hide-title':''?>">
											<a href="<?=$lvl3['link']?>">
											<?php if($lvl3['image']!=null && $lvl3['image']!=''):?>
												<span class="img-normal"><img src="<?=$lvl3['image']?>" alt="" /></span>
											<?php endif;?>
											<?php if($lvl3['image_hover']!=null && $lvl3['image_hover']!=''):?>
												<span class="img-hover"><img  src="<?=$lvl3['image_hover']?>" alt="" /></span>
											<?php endif;?>
												<span class="title"><?=$lvl3['title']?></span>
											</a>
										</li>
										<?php 
											endforeach; // end lvl2
										?>	
									</ul>
								<?php 
									endif;
								?>
							</li>
							<?php 
								endforeach; // end lvl2
							?>	
						</ul>
					<?php 
						endif;
					?>
				</li>
				<?php 
					endforeach; // end lvl1
				?>
			</ul>
		<?php 
			endif;
		?>
	</li>
	<?php 
		endforeach; // end lvl0
	?>
	</ul>
</div>