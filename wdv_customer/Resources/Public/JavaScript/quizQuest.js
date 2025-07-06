!function(window, document, $, undefined){

    var sel = {//selectors
            swiper: '.swiper-container-v',
            pagination: '.swiper-pagination-v',
            question: '.question',
            activeElm: '.swiper-slide-active',
            prevElm: '.swiper-slide-prev',
            nextElm: '.swiper-slide-next',
            slide: '.swiper-slide',
            progressBar: '.progress-v .progress-bar',
            quizcardBilder: '.quizcard.bilder',
            quizcardSlider: '.quizcard.slider',
            hiddenText: '.hiddentext',
            correct: '.correct',
            selected: '.selected',
            bilderAlert: '.bilder-alert',
            quizQuest: '#quiz-quest',
            quizQuestStarter: '#quizquest-starter',
            quizQuestIntro: '.quizquest-intro',
            swiperWrapperH: '.swiper-wrapper-h'
        },
        cl = {//classes
            swipeHint: 'swipe-hint',
            showSwipeHint: 'show-swipe-hint',
            wrong: 'wrong',
            answer: 'answer-',
            selected: 'selected',
            correct: 'correct',
            uncorrect: 'uncorrect',
            bilderHint: 'bilder-hint',
            noDisplay: 'no-display',
            tippAlert: 'tipp-alert',
            tippHint: 'tipp-hint',
            answered: 'answered'
        },
        dat = {//data
            correct: 'correct',
            questionType: 'question-type'
        },
        $swiperElm = $(sel.swiper),
        swiperParams = {
            hashnav: true,
            direction: 'horizontal',
            spaceBetween: 50,
            onInit: init,
            /*onSlideChangeEnd: slideChangeEnd,*/ //Currently not used because effect 'coverflow' prevents firing of this event
            onTouchMove: touchMove,
            onTransitionStart: transitionStart,
            onTransitionEnd: transitionEnd,
            paginationClickable: false,
            pagination: '.swiper-pagination-v',
            /*nextButton: '.icon-next',
            prevButton: '.icon-prev'*/
            nextButton: '.icon-next2',
            prevButton: '.icon-prev2'
        },
        score = {};

    if(! $('html').hasClass('is-ie')){
        swiperParams.effect = 'coverflow';
        swiperParams.coverflow = {
            rotate: 25,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows : false
        }
    }

    var swiper = new Swiper(sel.swiper, swiperParams);

    var swiperHorizontal = {};

    $('.swiper-container-h').each(function(index) {
        var id = $(this).parent().data('hash');
        swiperHorizontal[index] = new Swiper('.swiper-container-h-'+id, {
            pagination: '.swiper-pagination-h-'+id,
            nextButton: '.swiper-button-next-h-' + id,
            prevButton: '.swiper-button-prev-h-' + id,
            paginationClickable: true,
            spaceBetween: 50,
            slidesPerView: 1
        });
    });


    // button
    $(sel.quizQuestStarter).on('click', function(e) {
        e.preventDefault();
        $(sel.quizQuestIntro).fadeOut(function() {
            $(sel.quizQuest).hide().removeClass(cl.noDisplay).fadeIn();
        });
    });
    // check if hash is set
    if(window.location.hash) {
        $(sel.quizQuestIntro).hide();
        $(sel.quizQuest).removeClass(cl.noDisplay);
    }


    $('.card-swiper-next').on('click', function(){
        swiper.slideNext();
    });

    // !!!this worked with vertical swipes!!!??
    //Event handler for single answer questions
    $(sel.question).find('input:radio').on('click', function(){

        var $this = $(this),
            $nextElm = getNextElm(),
            $questionElm = $this.closest(sel.question),
            type = $questionElm.data(dat.questionType);

        if(type == 'right/wrong'){
            if($nextElm.is('.result')){
                if($this.data(dat.correct) === true){
                    $nextElm.removeClass(cl.wrong);
                }else{
                    $nextElm.addClass(cl.wrong);
                }
            }
        }else if(type == 'multiple'){
            if($nextElm.is('.result')){
                markWithGivenAnswer($this.data('index'), $nextElm);
            }
        }

        setScore($this, $questionElm);
        markAsAnswered($questionElm);

        setTimeout(goNext, 550);
    });

    function setScore($answer, $question){
        if($(sel.swiper).data('track-score') === true){
            score[$question.data('hash')] = $answer.data('score');

            // We set the calculated score on the result page directly every time.
            // This avoids seeing the placeholder being replaced during transition.
            displayResultScore();

            // console.log(score);
        }
    }

    // function sortByPointRangeDesc($a, $b){
    // var pointsA = $a.data('point-range').replace(/^\d-/, ""),
    // pointsB = $b.data('point-range').replace(/^\d-/, "");


    // }

    function displayResultScore(){
        if($(sel.swiper).data('track-score') === true){
            var $elm = $('.quizcard.final-result, .quizcard.facebookshare'),
                $alternatives = $elm.find('.final-result__alternative'),
                result = 0,
                //scoreText = displayResultScore.scoreText || (displayResultScore.scoreText = $elm.find('.score').text()),
                key;

            for(key in score){
                result += parseInt(score[key]);
            }

            // Show alternatives according to score
            $alternatives.hide().each(function(index, elm){
                var $elm = $(elm),
                    pointRangeStr = String($(elm).data('point-range')),
                    pointRangeStr = pointRangeStr.length === 1 ? pointRangeStr + "-" + pointRangeStr : pointRangeStr,
                    pointRange = pointRangeStr.match(/(\d+)-(\d+)/);

                if(result >= pointRange[1] && result <= pointRange[2]){
                    $elm.show();
                }
            });

            // console.log('result: ', result);
            //$elm.find('.score').text(scoreText.replace('!!score!!', result));
        }
    }


    // !!!this worked with horizontal swipes!!!??
    /*$(sel.question).find('.input').on('click', function(){

        var $this = $(this).find('input:radio'),
            $nextElm = getNextElm(),
            $questionElm = $this.closest(sel.question),
            type = $questionElm.data(dat.questionType);

        $this.prop("checked", true);

        if(type == 'right/wrong'){
            if($this.data(dat.correct) === true){
                $nextElm.removeClass(cl.wrong);
            }else{
                $nextElm.addClass(cl.wrong);
            }
        }else if(type == 'multiple'){
            $nextElm.removeClass (function (index, css) {
                //Remove any 'testX' class from next element
                //Regex is: (^|\s)answer-\S+/g
                return (css.match(new RegExp('(^|\\s)' + cl.answer + '\\S+', 'g')) || []).join(' ');
            }).addClass(cl.answer + ($this.data('index'))).removeClass('not-answered');
        }

        markAsAnswered($questionElm);

        setTimeout(goNext, 550);
    });*/

    //Slider Bildergalerie mit Text
    $(sel.quizcardSlider).each(function(){
        var $this = $(this),
            $slideHolder = $this.find(sel.swiperWrapperH),
            $tippAlert = $('<p class="' + cl.tippAlert + '"></p>');

        //$slideHolder.addClass('sliderlist'); //why?
        $this.append($tippAlert);

        $slideHolder.on('click', 'img', function(e){
            var $this = $(this);

            e.preventDefault();

            $tippAlert.text($this.attr('title'))
                .addClass(cl.tippHint);

            setTimeout(function() {
                $tippAlert.removeClass(cl.tippHint);
            }, 5000);
        });
    });

    //Event handler for multiple answer questions (image tiles)
    $(sel.quizcardBilder).on('click', '.quest-content', function(){
        var $this = $(this),
            $parent = $this.closest(sel.quizcardBilder),
            $bilderAlert = $parent.find(sel.bilderAlert),
            $hiddenText = $this.find(sel.hiddenText),
            alerttext = $hiddenText.text(),
            allCorrect;

        $this.addClass(cl.selected);

        if($hiddenText.hasClass(cl.correct)){
            $bilderAlert.removeClass(cl.uncorrect)
                .addClass(cl.correct);

            $this.find('.img-text')
                .addClass("icon_correct");
        }else{
            $bilderAlert.removeClass(cl.correct)
                .addClass(cl.uncorrect);

            $(this).find('.img-text')
                .addClass("icon_uncorrect");
        }

        $bilderAlert.text(alerttext);
        $bilderAlert.addClass(cl.bilderHint);

        allCorrect = allCorrectAnswersGiven($parent.find('.quest-content'));
        setTimeout(function() {
            $bilderAlert.removeClass(cl.bilderHint);

            if(allCorrect){
                markAsAnswered($parent);
                goNext();
            }
        }, 2000);
    });

    function init(swiper){
        setMaxDivHeights();
        updateProgressBar(swiper);
        lockUnlock(swiper);
    }

    function allCorrectAnswersGiven($answers){
        var expectedCorrectAnswers = $answers.find(sel.hiddenText + sel.correct),
            checkedCorrectAnswers = $answers.filter(sel.selected).find(sel.hiddenText + sel.correct);

        return expectedCorrectAnswers.length == checkedCorrectAnswers.length;
    }

    //currently not used
    function onlyCorrectAnswersGiven($answers){
        var incorrectCheckedAnswers = $answers.filter('[data-' + dat.correct + '!="true"]:checked');

        return allCorrectAnswersGiven($answers) && incorrectCheckedAnswers.length == 0;
    }

    function slideChangeEnd(swiper){


    }

    function transitionStart(swiper){
        // last classes
        if (swiper.slides.length-1 == swiper.snapIndex) {
            $swiperElm.parent().addClass('last');
        } else {
            $swiperElm.parent().removeClass('last');
        }
        if (swiper.slides.length-2 == swiper.snapIndex) {
            $swiperElm.parent().addClass('penultimate');
        } else {
            $swiperElm.parent().removeClass('penultimate');
        }
    }

    function transitionEnd(swiper){
        //START: using this event instead of slideChangeEnd
        // var $activeElm = getActiveElm();

        updateProgressBar(swiper);

        //Lock/unlock question cards
        // if($activeElm.is(sel.question) && !$activeElm.is('.' + cl.answered)){
        // swiper.lockSwipeToNext();
        // //$swiperElm.parent().addClass('swiper-button-disabled' + ' ' + cl.showSwipeHint);
        // $swiperElm.parent().addClass(cl.showSwipeHint);
        // $('.swiper-button-next').addClass('swiper-button-disabled');
        // }/* else if($activeElm.prev().is(sel.question)){
        // swiper.lockSwipeToPrev();
        // $swiperElm.parent().removeClass('swiper-button-disabled'  + ' ' + cl.showSwipeHint);
        // $('.swiper-button-prev').addClass('swiper-button-disabled');
        // } */ else {
        // swiper.unlockSwipeToNext();
        // //$swiperElm.parent().removeClass('swiper-button-disabled swiper-prev-disabled'  + ' ' + cl.showSwipeHint);
        // $swiperElm.parent().removeClass(cl.showSwipeHint);
        // $('.swiper-button-next').removeClass('swiper-button-disabled');
        // }
        lockUnlock(swiper);

        $swiperElm.removeClass(cl.swipeHint);

        //Piwik.getAsyncTracker().trackPageView($activeElm.data('title')); //TODO: comment in to use Piwik

        //END: using this event instead of slideChangeEnd

        $swiperElm.removeClass('touchMove');
    }

    function lockUnlock(swiper){
        var $activeElm = getActiveElm();

        if($activeElm.is(sel.question) && !$activeElm.is('.' + cl.answered)){
            swiper.lockSwipeToNext();
            $swiperElm.parent().addClass(cl.showSwipeHint);
            $('.swiper-button-next').addClass('swiper-button-disabled swiper-button-disabled--wdv disabled btn-grey');
        } else {
            $swiperElm.parent().removeClass(cl.showSwipeHint);

            // Leave last slide locked
            if(swiper.progress !== 1){
                swiper.unlockSwipeToNext();
                $('.swiper-button-next').removeClass('swiper-button-disabled swiper-button-disabled--wdv disabled btn-grey');
            }
        }
    }

    function touchMove(swiper, e){
        if(! swiper.params.allowSwipeToNext && $swiperElm.parent().hasClass(cl.showSwipeHint)){
            handleSwipeHint();
        } else {
            $swiperElm.addClass('touchMove');
        }
    }

    $('.icon-next').on('click', function(){
        if($('.quiz-wrapper--quizcards').hasClass('swiper-button-disabled swiper-button-disabled--wdv')) {
            handleSwipeHint();
        }
    });

    function handleSwipeHint(){
        //This additional timeout might be useful if the strange Chrome bug reappears
        //where a touchmove was fired on a mere click.
        //The question click handlers might then call a function that deletes the handler
        //thereby stopping the message from being shown when clicking on answers.
        //touchMove.timeoutHandle = setTimeout(function(){
        $swiperElm.addClass(cl.swipeHint);
        setTimeout(function() {
            $swiperElm.removeClass(cl.swipeHint);
            $swiperElm.parent().removeClass(cl.showSwipeHint);
        }, 3000);
        //}, 0);
    }


    function markAsAnswered($elm){
        $elm.addClass(cl.answered);
    }

    function markWithGivenAnswer(answerIndex, $nextElm){
        $nextElm.removeClass (function (index, css) {
            //Remove any 'answer-X' class from next element
            //Regex is: (^|\s)answer-\S+/g
            return (css.match(new RegExp('(^|\\s)' + cl.answer + '\\S+', 'g')) || []).join(' ');
        }).addClass(cl.answer + answerIndex).removeClass('not-answered');
    }

    function goNext(){
        swiper.unlockSwipeToNext();
        swiper.slideNext();
    }

    function getActiveElm(){
        return $swiperElm.find(sel.activeElm);
    }

    function getNextElm(){
        return $swiperElm.find(sel.nextElm);
    }

    function getPrevElm(){
        return $swiperElm.find(sel.prevElm);
    }

    function updateProgressBar(swiper){
        var percent = swiper.progress * 100;
        $(sel.progressBar).width(percent + '%').attr('aria-valuenow', percent);
        $(sel.progressBar).find(".progress-value").text(percent + '%');
    }
    function setMaxDivHeights(){

        var quizHeight = $(".quiz--center").height();
        var heightString = quizHeight + "px";
        $("#quiz .swiper-container").css("height", heightString).css("max-height", heightString);
        $("#quiz .swiper-container-2").css("height", heightString).css("max-height", heightString);
        $("#quiz .swiper-wrapper").css("max-height", heightString);

        var facebookcardHeadHeight = $("#quiz .final-result__alternative .result__text-wrapper").height();
        var facebookcardBodyHeight = $("#quiz .final-result__alternative .card-body").height();
        $("#quiz .final-result__alternative .card-body img").css("height", facebookcardBodyHeight);

        // console.log('quizHeight:' + quizHeight);
    }

}(window, document, jQuery)