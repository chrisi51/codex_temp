/*
<body>
    <div>
        [..]
        <div>
            <a class="wx_overlay_opener" href="#overlay_ritzbitz"></a>

            <div class="wx_overlay" id="overlay_ritzbitz">
                jsfljsflsjdflsdkfj
            </div>
        </div>
    </div>
</body>

===>

<body>
    <div>
        [..]
        <div>
            <a class="wx_overlay_opener" href="#overlay_ritzbitz"></a>
        </div>
    </div>

    <div class="wx_overlay" id="overlay_ritzbitz">
        <div class="wx_overlay_content">
            <div class="wx_overlay_closer"></div>
            jsfljsflsjdflsdkf
        </div>
        <div class="wx_overlay_shadow"></div>
    </div>
</body>

*/


/**
 * Author: 
    Christian Hillebrand <typo3@webxass.de> 
 */

let WxOverlay = {

    /**
     * Initialize 
     */
    initialize: function () {

        let overlays = $('.wx_overlay');
        if (overlays.length > 0) {
            
            overlays.each(function () {
                var overlay = $(this);
                var content = $("<div class='wx_overlay_content'></div>");
                var closer = $("<div class='wx_overlay_closer'></div>");
                var shadow = $("<div class='wx_overlay_shadow'></div>"); 

                overlay.wrapInner(content);
                overlay.append(shadow);
                $("body").append(overlay);

                content = overlay.find(".wx_overlay_content");
                content.append(closer);
                closer = overlay.find(".wx_overlay_closer");

                $([shadow.get(0),closer.get(0)]).on("click", function(){
                    WxOverlay.toggleOverlay(overlay);
                });
            });
            
            
            $(".wx_overlay_opener").on("click",function(event){
                overlay = $($(this).attr("href"));
                WxOverlay.toggleOverlay(overlay);
                event.preventDefault();
            })

        }
        
        /* prüfen ob ein Anker existiert*/
        if (window.location.href.search(/\#/i) != -1 && window.location.href.split("#")[1].split("?")[0].length > 0)
        {
            my_current_location = window.location.href.split("#")[1].split("?")[0];
            /* prüfen, ob anker valide ist */
            if(my_current_location.length > 0 && $("#"+my_current_location).length > 0){
                target = $("#"+my_current_location);
                /* prüfen ob Anker ein Overlay ist */
                if(target.hasClass("wx_overlay")){
                    /* overlay öffnen */
                    WxOverlay.toggleOverlay(target);
                }
            }
        }    
        
    },
    
    toggleOverlay: function (overlay) {
        overlay.toggleClass("open");
    }
}


          
$(document).ready(function () {
    WxOverlay.initialize();
});
