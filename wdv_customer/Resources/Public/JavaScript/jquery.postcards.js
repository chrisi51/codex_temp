/**
 * jQuery Postcards; version: 1.0.0
 * Author: http://wacon.de
 * Copyright (c) 2023 WACON Internet GmbH; MIT License
 * @preserve
 */

(function( $ ) {
    $.fn.postcards = function( options ) {
        const defaults = {
            selectors: {
                shareLink: ".link-share",
                postcardLink: ".link-postcard",
                postcard: ".mask-postcard",
                cta: ".btn"
            }
        };

        const settings = $.extend( {}, defaults, options );

        // Create an IntersectionObserver object.
        const observer = new IntersectionObserver((entries, observer) => {
            for (const entry of entries) {
                // Get the element that is being observed.
                const element = entry.target;
            
                // Check if the element is in the viewport.
                if (entry.isIntersecting) {
                    $(entry).attr("data-visible", "true");
                }
            }
        });        

        return $(this).each(function(){
            observer.observe($(this).get(0));
            setURLForAllPostcards(this);
            autoOpenLightboxIfPostcardIsTargeted(this);
            connectCTAToLightbox(this);
        });

        /**
         * Set all URLs for all postcards
         * @param Object cObj
         * @returns void
         */
        function setURLForAllPostcards(cObj) {
            let postcards = $(cObj).find(settings.selectors.postcard);

            // Set URL for all postcards
            postcards.each(function(){
                //Replace %link% with current URL + Postcard ID as hash
                let shareLinkElement = $(this).find(settings.selectors.shareLink);

                if (shareLinkElement.length > 0) {
                    let currentURL = getCurrentUrl();
                    currentURL = prepareCurrentURL(currentURL);
                    const postcardHash = getPostcardHash(this);
                    let postcardLink = currentURL + "#" + postcardHash;
                    shareLinkElement.attr("href", shareLinkElement.attr("href").replace("%25link%25", encodeURIComponent(postcardLink).trim()));
                }
            });
        }

        /**
         * Auto open lightbox, if a postcard id is inside the window.location.hash
         * @param Object cObj
         * @return void
         */
        function autoOpenLightboxIfPostcardIsTargeted(cObj) {
            if (window.location.hash == "" || typeof window.location.hash == "undefined") {
                return;
            }

            const hashTarget = $(window.location.hash);
            const lightboxOpener = hashTarget.find(settings.selectors.postcardLink)

            if (hashTarget.length == 0 || lightboxOpener.length == 0 || !hashTarget.hasClass("mask-postcard") || lightboxOpener.attr("data-toggle") != "lightbox") {
                return;
            }

            // Now we know, that hashTarget is a lightbox link
            openLightboxAfterAutoScrollIsDone(cObj, lightboxOpener);
        }

        /**
         * Open given lightbox, if auto scrolling is done
         * @param Object cObj
         * @param Object hashTarget 
         */
        function openLightboxAfterAutoScrollIsDone(cObj, hashTarget) {
            if ($(cObj).attr("data-visible") != "true") {
                setTimeout(function() {
                    hashTarget.trigger("click");
                }, 100);
            }else {
                hashTarget.trigger("click");
            }
        }

        /**
         * Return the current url
         * @returns String
         */
        function getCurrentUrl() {
            return window.location.href;
        }

        /**
         * We remove all unnessecary stuf from the url
         * @param String url
         * @returns String
         */
        function prepareCurrentURL(url) {
            // Remove hash
            url = url.split('#')[0];

            return url;
        }

        /**
         * Return the Hash value for the given postcard element
         * @param Object element 
         * @returns String
         */
        function getPostcardHash(element) {
            return $(element).attr("id");
        }

        /**
         * If click on CTA, then open lightbox
         * @param Object element 
         */
        function connectCTAToLightbox(element) {
            $(element).find(settings.selectors.cta).on("click", function(e){
                $(this).prev(settings.selectors.postcardLink).trigger("click");
            });
        }
    }; 

    $(document).ready(function(){
        $(".mask-postcards").postcards();
    });
})( jQuery );