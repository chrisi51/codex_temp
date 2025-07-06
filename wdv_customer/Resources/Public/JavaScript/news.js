$(document).ready(function() {
	// Hide last 4 News-Entries in dynamic area of news on the startpage
    $('#c234 .news-list').hide(function() {
        $(this).hide();
        // let newsInC234AreHidden = true;
        // alert(newsInC234AreHidden);
    });

    // Show "news-more" and setup functionality
    $('.tx_news .news-more').show(function() {
        let container = $(this).parent('.tx_news');
        // alert(container.attr('class'));

        // let buttton = container.find('.load-more');
        // let paginationHuman = container.find('.news-paginate-human ul.pagination');
        let paginationAjax = container.find('.news-paginate-ajax ul.pagination');

        let newsToLoad = [];

        // let dateGroupsInLoadedNews = [];

        // Grab the amount of pages to be showable
        // from paginationAjax (All Pages and Links without "...")
        paginationAjax.find('li:not(.active)').each(function() {
            newsToLoad.push($(this).find('a').attr('href'));
        });

        let allPaginationPages = newsToLoad.length;

        // Button Event & Ajax-Request
        $(this).not('.loading').find('a:visible').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Get the uid of the actual content element of the tx_news plugin
            // and use the uid as parameter in the ajax call to prevent loading of
            // multiple instances of this plugin (eg. on pages with tabs and multiple
            // usings of tx_news...
            let nlvceuid = container.parent().attr('id').replace('c', '');

            // If we are in an element with content uid 234, just show the first elements which are hidden (line line to 16).
            // When then the load more button is clicked, load more news by proceeding with the actual ajax call
            // alert(newsToLoad.length + " " + antl);
            if (nlvceuid === 234 && newsToLoad.length === allPaginationPages) {
                $('#c234 .news-list').fadeIn('slow');
                allPaginationPages = allPaginationPages + 1;
            } else {
                // Check if we have get-parameters already in urls from pagination-ajax entrys
                let urlStringPresent = newsToLoad[0];
                let appendParametersString =  'loadnews?nlvceuid=' + nlvceuid;

                // remove .html from string
                urlStringPresent = urlStringPresent.replace(".html", "")

                let urlStringWithParametersString = urlStringPresent + '/' + appendParametersString;

                // If there are parameters
                // let urlStringWithParametersString = urlStringPresent + '&' + appendParametersString;
                // if(urlStringPresent.indexOf('?') === -1)
                //     urlStringWithParametersString = urlStringPresent + '?' + appendParametersString;

                $.ajax({
                    async: true,
                    url: urlStringWithParametersString,
                    type: 'GET',
                    dataType: 'html',
                    success: function(data) {
                        let testinger = $(data).find('.card-items');
                        //console.log("Length: " + testinger.length);
                        if (testinger.length > 0) {
                            container.find('.news-paginate-human').prev('.last').addClass('mb-3');
                        } else {
                            container.find('.news-paginate-human').prev('.last').addClass('mb-5');
                        }
                        container.find('.news-paginate-human').prev('.last').removeClass('last');

                        // Here we insert the requested contents
                        // var jqObj = $(data);
                        let content = $(data).find('.ajax-content').html();
                        container.find('.news-paginate-human').before($(content).hide().fadeIn('4000'));

                        newsToLoad.shift();

                        // Remove button if there is nothing more to load
                        if (newsToLoad.length == 0) {
                            $(this).remove();
                        }

                        // Unveil new images
                        $("img").unveil(0, function() {
                            $(this).trigger("unveil");
                            // alert($(this).attr('src') + " unveiled!");
                            // Adjust Focuspoint for new loaded Elements
                            $('.focuspoint').adjustFocus();
                        });
                    },
                    error: function(error) {
                        console.error(error)
                        $(this).removeClass('loading');
                        // alert('Error fetching data. Please try again later!');
                    }
                });
            }
            return true;
        });
    });
});