<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/26
 * Time: 11:33
 */
?>

<style>
#open_in_safari {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 20000; display: none;
}
</style>

<video id='android_video' src="<?php echo $carema['carema']?>" style="width:100%;" controls autoplay loop>
	<source type='video/mp4' />
</video>

<div id="vlc" style="padding: 1em;">
	<br/>
	<p align="center">
		<a id="ios_video" href='#'>
			<img width="50%" src="images/vlc.png" /></a>
	</p>
	<br/>
	<h4>播放视频需要使用VLC软件，如未安装请先点击顶部链接或
		<a href="https://appsto.re/cn/QR_WM.i" style="text-decoration: underline;">这里</a>
		安装播放软件。</h4>
	<h4>如果您已经安装VLC，点击顶部链接或
		<a href="vlc-x-callback://x-callback-url/stream?url=<?php echo $carema['carema']?>" style="text-decoration: underline;">这里</a>
		播放视频。</h4>
</div>

<div id="open_in_safari"><img src="images/guide.png"></div>

<script>
	// process script.
	function is_weixin() {
		var ua = navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i)=="micromessenger") {
			return true;
		} else {
			return false;
		}
	}

	var ua = navigator.userAgent.toLowerCase();
	if (/iphone|ipad|ipod/.test(ua)) {
		// is ios	
		document.getElementById('android_video').parentNode.removeChild(
			document.getElementById('android_video'));

		// add meta to head for downloading software.
		var oMeta = document.createElement('meta');
		oMeta.name = 'apple-itunes-app';
		oMeta.content = 'app-id=650377962, app-argument=vlc-x-callback://x-callback-url/stream?url=<?php echo $carema['carema']?>';
		document.getElementsByTagName('head')[0].appendChild(oMeta);

		// load videos.
		document.getElementById('ios_video').onclick = function(e) {
			var appUrlScheme = 'vlc-x-callback://x-callback-url/stream?url=<?php echo $carema['carema']?>';
			window.open(appUrlScheme, '_self');
		};
	} 
	else {
		// is android or others.
		document.getElementById('vlc').style.display = 'none';
	}

	// if ios system & wechat internal browser.
	if (/iphone|ipad|ipod/.test(ua) && is_weixin()) {
		document.getElementById('open_in_safari').style.display = 'block';
		document.getElementById('vlc').style.display = 'none';
	}
</script>
