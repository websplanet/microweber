<? 
 //p($pictures);
 ?>

<div class="rounded_box transparent" id="set_gallery_img_z">


 <a class="jqzoom" id="set_gallery_img_big" href="<? print get_media_thumbnail( $pictures[0]['id'] , '900')  ?>" > <img id="set_gallery_img" src="<? print get_media_thumbnail( $pictures[0]['id'] , 300)  ?>" width="250" alt="" /> </a>










  <div class="lt"></div>
  <div class="rt"></div>
  <div class="lb"></div>
  <div class="rb"></div>
</div>
<br/>
<div id="gallery">
  <? if(!empty($pictures)): ?>
  <? foreach($pictures as $pic): ?>
  <a href="javascript:set_gallery_img('<? print get_media_thumbnail( $pic['id'] , 400)  ?>', '<? print get_media_thumbnail( $pic['id'] , '900')  ?>');"><img src="<? print get_media_thumbnail( $pic['id'] , 250)  ?>"  height="120" alt="" /></a>
  <? endforeach ;  ?>
  <? endif; ?>
</div>
