<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/26
 * Time: 11:33
 */
?>
<script>
	window.onload = function() {
        var ua = navigator.userAgent.toLowerCase();
        if (/iphone|ipad|ipod/.test(ua)) {
			document.getElementById('open_in_safari').style.display = 'block';
			document.getElementById('android_video').style.display = 'none';
			location.href='vlc-x-callback://x-callback-url/stream?url=<?php echo $carema['carema']?>';
        }
	}
</script>
<video id='android_video' src="<?php echo $carema['carema']?>"  controls autoplay loop>
    <source type='video/mp4' />
</video>
<video id='android_video' src="http://www.fe-dna.com/frontend/web/upload/v2.mp4"  controls autoplay loop>
    <source type='video/mp4' />
</video>

<p style="padding: 1em;">
	如果播放不了，请：
	<a id='open_in_safari' href="javascript:location.href='https://appsto.re/cn/QR_WM.i';"><h3>请先下载vlc来查看</h3></a>
	<a href="javascript:location.href='vlc-x-callback://x-callback-url/stream?url=<?php echo $carema['carema']?>'"><h3>如果您已经下载了，请点击打开</h3></a>
</p>