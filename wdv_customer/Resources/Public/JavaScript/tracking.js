//console.log("tracking");
//cookiecheck have to be modified
/*
if(wx_isCookieAllowed("s_pers") && wx_isCookieAllowed("s_sess")){
	console.log("tracking enabled");
	var script = document.createElement('script');
	script.src = "//anonym.aok.de/launch/f55fded51cfd/e40dc0508c08/launch-1bd90fe5d1ff.min.js";

	document.head.appendChild(script);

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
