/*
if(wx_isCookieAllowed("_pk_ses.*") && wx_isCookieAllowed("_pk_id.*")){

	var _paq = window._paq = window._paq || [];
	/* tracker methods like "setCustomDimension" should be called before "trackPageView" 
	_paq.push(['trackPageView']);
	_paq.push(['enableLinkTracking']);
	(function() {
	var u="https://matomo.wdv.de/";
	_paq.push(['setTrackerUrl', u+'matomo.php']);
	_paq.push(['setSiteId', '64']);
	var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
	})();

}
*/

function wx_matomo(){
	if(
		wx_isCookieAllowed("s_ecid",true) && 
		wx_isCookieAllowed("AMCV_*",true) &&
		wx_isCookieAllowed("s_cc",true) &&
		wx_isCookieAllowed("s_sq",true) &&
		wx_isCookieAllowed("s_vi",true) &&
		wx_isCookieAllowed("s_fid",true)
	){
		console.log("tracking enabled");
		var script = document.createElement('script');
		script.src = "//anonym.aok.de/launch/f55fded51cfd/e40dc0508c08/launch-1bd90fe5d1ff.min.js";

		document.head.appendChild(script);
	}
}

wx_matomo();