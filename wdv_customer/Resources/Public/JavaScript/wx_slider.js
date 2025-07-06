/**
 * Author: 
    Christian Hillebrand <c.hillebrand@arts-others.de> 
    Christian Hillebrand <typo3@webxass.de> 
 */

var touchdevice = (('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));

/*
 * Navigation
 */
$(".wx_slider:not(.accordeon)").each(function () {
	var activeSlide = 0;
	var slider = $(this);
    var highestSlide = 0;
    var videos = slider.find("video:not([autoplay][muted])");
	var navitems = slider.find("nav .item");
	var capitems = slider.find("nav .capitem");
	var counter_index = slider.find("nav .counter .index");
	var counter_count = slider.find("nav .counter .count");
	var navselect = slider.find("nav .select");
	var slides = slider.find("nav .item").length;
	if(slides == 0 )slides = slider.find(".slidercontent > .scene > ul > li").length;
	var sliderLi = slider.find(".slidercontent > .scene > ul > li");
	var animation = "slide";
	if(slider.hasClass("fade")) animation = "fade";
    var slideToOpen = 0;
    var my_current_location;

    counter_count.html(slides);
    
    /* prüfen ob ein Anker existiert*/
    if (window.location.href.search(/\#/i) != -1 && window.location.href.split("#")[1].length > 0)
    {
        my_current_location = window.location.href.split("#")[1];
        if(my_current_location.length > 0 && $("#"+my_current_location).length > 0 && sliderLi.filter("#"+my_current_location+":visible").length > 0){
            /* Slide des Ankers merken und auf anker des erstens Slides umrouten*/
            slideToOpen = sliderLi.index(sliderLi.filter("#"+my_current_location));
            window.location.href="#";
        }
    }

    openSlide(slideToOpen);

    /* wenn umgeroutet wurde, die URL wieder zurücksetzen und dann verzögert noch auf den parent des Sliders scrollen damit die navi noch im bild ist */
    if(slideToOpen > 0){
        setTimeout(function(){
                AoSmoothScroll.scrollTo("#"+slider.attr("id"));
                history.replaceState({page:0}, "", "#"+my_current_location);
        },500);
    }   
    
	switch (animation){
		case "fade":
            var temp_highest_slide=0;
            sliderLi.find(">.content").each(function () {
                if($(this).innerHeight() > temp_highest_slide){
                    temp_highest_slide = $(this).innerHeight();
                    highestSlide = $(this);
                }
            });
            if(highestSlide) highestSlide.parent().css("position","relative");
            
			sliderLi.css("transition","opacity 1s").css("opacity","0");		
			sliderLi.first().css("opacity","1").css("zIndex","10");
			break;
		default:
			slider.find(".slidercontent > .scene > ul").css("width", (slides*100)+"%").css("transition","transform 1s");
			if(slider.hasClass("opacity")){
				sliderLi.css("transition","opacity 1s");
				sliderLi.not(":eq(0)").css("opacity", "0.2")
			}
	}
	
	if(navselect){
		if(!touchdevice){
			navselect.on("mouseenter", function(){
				$(this).addClass("open");
			});
		}else{
			navselect.on("touch click", function(){
				$(this).toggleClass("open");
			});
		}
		navselect.on("mouseleave", function(){
			$(this).removeClass("open");
		});
	}
	
	navitems.on("click", function(){
		var index = $(this).index();
		if($(this).parent().hasClass("options")) index += $(this).closest("nav").find("> .item").length;
		openSlide(index);
	});
	
    /* keine Ahnung wofür das mal war, finde aktuell keine Vorkommen */
	slider.find(".nextarrow").on("click", nextSlide);

	slider.find(".arrows.next").on("click", nextSlide);
	slider.find(".arrows.prev").on("click", prevSlide);
    
    
	sliderLi.on("swipeleft", prevSlide);
	sliderLi.on("swiperight", nextSlide);
	
	function nextSlide(){
		if (activeSlide == slides - 1){
			openSlide(0);
		}else{
			openSlide(activeSlide+1);			
		}
	}
	
	function prevSlide(){
		if (activeSlide == 0){
			openSlide(slides-1);
		}else{
			openSlide(activeSlide-1);			
		}
	}
	
	function openSlide(index){
		//console.log(index,navitems.eq(index));
		activeSlide = index;
        
        counter_index.html(activeSlide+1);
		videos.trigger('pause');
		navitems.removeClass("active");
		navselect.removeClass("active");
		capitems.removeClass("active");
		navitems.eq(index).addClass("active");
		if(navitems.eq(index).parent().hasClass("options")) navselect.addClass("active");
		capitems.eq(index).addClass("active");
        
		switch (animation){
			case "fade":
				sliderLi.css("opacity","0").css("zIndex","1");
				sliderLi.eq(index).css("opacity","1").css("zIndex","10");
				break;
			default:
				slider.find(".slidercontent > .scene > ul").css("transform","translateX(-"+100/slides*index+"%)");
				if(slider.hasClass("opacity")){
					sliderLi.not(":eq("+index+")").css("opacity", "0.2");
					sliderLi.eq(index).css("opacity", "1");
				}
		}
	}
	
	if(slider.hasClass("auto")){
		setInterval(nextSlide, 5000);
	}
		

	var touchstartX = 0;
	var touchendX = 0;
	var touchstartY = 0;
	var touchendY = 0;

	function handleGesture() {
		//console.log(touchstartY, touchendY);
		if(Math.abs(touchstartY-touchendY) < 50 && Math.abs(touchstartX - touchendX) > 50){
			//console.log(touchstartX, touchendX);
			if (touchendX < touchstartX) nextSlide();
			if (touchendX > touchstartX) prevSlide();
		}
	}

	slider.on('touchstart', function(e) {
		touchstartX = e.changedTouches[0].screenX;
		touchstartY = e.changedTouches[0].screenY;
	});

	slider.on('touchend', function(e) {
		touchendX = e.changedTouches[0].screenX;
		touchendY = e.changedTouches[0].screenY;
		handleGesture();
	})
/*
 * touchevent per mouse
 *
	slider.on('mousedown', function(e) {
		touchstartX = e.pageX;
		touchstartY = e.pageY;
	});

	slider.on('mouseup', function(e) {
		touchendX = e.pageX;
		touchendY = e.pageY;
		handleGesture();
	});
*/
});
