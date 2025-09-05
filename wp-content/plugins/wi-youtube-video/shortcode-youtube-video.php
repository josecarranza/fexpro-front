<?php if($youtube_link!=""): ?>

<style>
	.wi-video-youtube{
		height: 100%;
		width: 100%;
	}
</style>
<iframe class="wi-video-youtube" width="560" height="315" src="https://www.youtube.com/embed/<?=$youtube_link ?>?autoplay=1&mute=1&loop=1&controls=0&playlist=<?=$youtube_link ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<?php endif; ?>