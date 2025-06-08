<?php
$banner=$VT->VeriGetir("banner","WHERE durum=?",array(1),"ORDER BY sirano ASC");
if($banner!=false)
{
?>

<div class="slider-container rev_slider_wrapper" style="height: 650px;">
	<div id="revolutionSlider" class="slider rev_slider" data-version="5.4.8" data-plugin-revolution-slider data-plugin-options="{'delay': 9000, 'gridwidth': 1400, 'gridheight': 650, 'navigation': {'arrows': {'enable': true, 'style': 'slider-arrows-style-1' }}}">
		<ul>
			<?php
				foreach($banner as $banner)
				{
		    // Get translation if available
        $banner_dil = $VT->VeriGetir("banner_dil","WHERE banner_id=? AND lang=?",array($banner["ID"],$dil),"ORDER BY ID ASC",1);
        
        // Use translated content if available, otherwise use default
        $baslik = !empty($banner_dil) && !empty($banner_dil[0]["baslik"]) ? $banner_dil[0]["baslik"] : $banner["baslik"];
        $aciklama = !empty($banner_dil) && !empty($banner_dil[0]["aciklama"]) ? $banner_dil[0]["aciklama"] : $banner["aciklama"];
        $url = !empty($banner_dil) && !empty($banner_dil[0]["url"]) ? $banner_dil[0]["url"] : $banner["url"];
			?>
			<li class="slide-overlay" data-transition="fade">
			  <?php if(!empty($banner["video"])): 
			    $video_path = __DIR__ . '/../images/banner/' . $banner["video"];
			    $video_exists = file_exists($video_path);
			  ?>
                <!-- Video Debug: 
                     File: <?=$banner["video"]?>
                     Path: <?=$video_path?>
                     Exists: <?=$video_exists ? 'Yes' : 'No'?>
                -->
                <img src="<?=!empty($banner["resim"]) ? SITE.'images/banner/'.$banner["resim"] : SITE.'assets/img/blank.png'?>" 
                    alt="<?=$baslik?>"
                    data-bgposition="center center" 
                    data-bgfit="cover" 
                    data-bgrepeat="no-repeat" 
                    class="rev-slidebg">
                <?php if($video_exists): ?>
			    <div class="rs-background-video-layer" 
                  data-videomp4="<?=SITE?>images/banner/<?=$banner["video"]?>"
                  data-videopreload="auto"
                  data-videoloop="loopandnoslidestop"
                  data-videowidth="100%"
                  data-videoheight="100%"
                  data-aspectratio="16:9"
                  data-autoplay="true"
                  data-autoplayonlyfirsttime="false"
                  data-nextslideatend="false"
                  data-forcerewind="on"
                  data-volume="mute">
                </div>
                <?php else: ?>
                <!-- Video file not found! -->
                <?php endif; ?>
			  <?php else: ?>
				  <img src="<?=SITE?>images/banner/<?=$banner["resim"]?>" 
					alt="<?=$baslik?>"
					data-bgposition="center center" 
					data-bgfit="cover" 
					data-bgrepeat="no-repeat" 
					class="rev-slidebg">
				<?php endif; ?>

				<div class="tp-caption main-label"
					data-x="center" data-hoffset="0"
					data-y="center" data-voffset="-45"
					data-start="1000"
					data-whitespace="nowrap"						 
					data-transform_in="y:[100%];s:500;"
					data-transform_out="opacity:0;s:500;"
					style="z-index: 5; font-size: 2.5em; font-weight: bolder; color: yellow;"
					data-mask_in="x:0px;y:0px;"><?=$baslik?></div>
				
				<div class="tp-caption bottom-label"
					data-x="center" data-hoffset="0"
					data-y="center" data-voffset="5"
					data-start="1000"
					data-transform_in="y:[100%];opacity:0;s:500;"
					data-transform_in="y:[100%];opacity:0;s:500;" 
					style="z-index: 5; font-size: 1.2em; line-height: 22px; color: yellow; font-weight: bold;"><?=$aciklama?></div>
				
				<?php
				if(!empty($url))
				{
					?>
					<a class="tp-caption btn btn-light-2 btn-outline font-weight-semibold"
						data-hash
						data-hash-offset="85"
						href="<?=$url?>"
						data-x="center" data-hoffset="0"
						data-y="center" data-voffset="75"
						data-start="1250"
						data-whitespace="nowrap"	
						data-transform_in="y:[100%];s:500;"
						data-transform_out="opacity:0;s:500;"
						style="z-index: 5; font-size: 1em; padding: 12px 35px;"
						data-mask_in="x:0px;y:0px;">Daha Fazla</a>
					<?php
				}
				?>
			</li>
			<?php
			}
			?>
		</ul>
	</div>
</div>
<?php } ?> 