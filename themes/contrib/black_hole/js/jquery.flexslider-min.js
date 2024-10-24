/*
 * jQuery FlexSlider v1.7
 * http://flex.madebymufffin.com
 * Copyright 2011, Tyler Smith
 * SEO optimized
 * https://www.radut.com
 * Copyright 2024, Dr. Florian Radut
 * Free to use under the MIT license.
 */
!function(e){e.flexslider=function(n,t){var a=n;a.init=function(){if(a.vars=e.extend({},e.flexslider.defaults,t),a.data("flexslider",!0),a.container=e(".slides",a),a.slides=e(".slides > li",a),a.count=a.slides.length,a.animating=!1,a.currentSlide=a.vars.slideToStart,a.animatingTo=a.currentSlide,a.atEnd=0==a.currentSlide,a.eventType="ontouchstart"in document.documentElement?"touchstart":"click",a.cloneCount=0,a.cloneOffset=0,""!=a.vars.controlsContainer&&(a.controlsContainer=e(a.vars.controlsContainer).eq(e(".slides").index(a.container)),a.containerExists=a.controlsContainer.length>0),""!=a.vars.manualControls&&(a.manualControls=e(a.vars.manualControls,a.containerExists?a.controlsContainer:a),a.manualExists=a.manualControls.length>0),a.vars.randomize&&(a.slides.sort((function(){return Math.round(Math.random())-.5})),a.container.empty().append(a.slides)),"slide"==a.vars.animation.toLowerCase()?(a.css({overflow:"hidden"}),a.vars.animationLoop&&(a.cloneCount=2,a.cloneOffset=1,a.container.append(a.slides.filter(":first").clone().addClass("clone")).prepend(a.slides.filter(":last").clone().addClass("clone"))),a.container.width((a.count+a.cloneCount)*a.width()+2e3),a.newSlides=e(".slides > li",a),setTimeout((function(){a.newSlides.width(a.width()).css({float:"left"}).show()}),100),a.container.css({marginLeft:-1*(a.currentSlide+a.cloneOffset)*a.width()+"px"})):a.slides.css({width:"100%",float:"left",marginRight:"-100%"}).eq(a.currentSlide).fadeIn(400),a.vars.controlNav){if(a.manualExists)a.controlNav=a.manualControls;else{for(var n=e('<ol class="flex-control-nav"></ol>'),i=1,s=0;s<a.count;s++)n.append('<li><a href="#" rel="nofollow">'+i+"</a></li>"),i++;a.containerExists?(e(a.controlsContainer).append(n),a.controlNav=e(".flex-control-nav li a",a.controlsContainer)):(a.append(n),a.controlNav=e(".flex-control-nav li a",a))}a.controlNav.eq(a.currentSlide).addClass("active"),a.controlNav.bind(a.eventType,(function(n){n.preventDefault(),e(this).hasClass("active")||a.flexAnimate(a.controlNav.index(e(this)),a.vars.pauseOnAction)}))}if(a.vars.directionNav){var o=e('<ul class="flex-direction-nav"><li><a class="prev" href="#" rel="nofollow">'+a.vars.prevText+'</a></li><li><a class="next" href="#" rel="nofollow">'+a.vars.nextText+"</a></li></ul>");a.containerExists?(e(a.controlsContainer).append(o),a.directionNav=e(".flex-direction-nav li a",a.controlsContainer)):(a.append(o),a.directionNav=e(".flex-direction-nav li a",a)),a.vars.animationLoop||(0==a.currentSlide?a.directionNav.filter(".prev").addClass("disabled"):a.currentSlide==a.count-1&&a.directionNav.filter(".next").addClass("disabled")),a.directionNav.bind(a.eventType,(function(n){n.preventDefault();var t=e(this).hasClass("next")?a.getTarget("next"):a.getTarget("prev");a.canAdvance(t)&&a.flexAnimate(t,a.vars.pauseOnAction)}))}if(a.vars.keyboardNav&&1==e("ul.slides").length&&e(document).keyup((function(e){if(!a.animating&&(39==e.keyCode||37==e.keyCode)){if(39==e.keyCode)var n=a.getTarget("next");else if(37==e.keyCode)n=a.getTarget("prev");a.canAdvance(n)&&a.flexAnimate(n,a.vars.pauseOnAction)}})),a.vars.slideshow&&(a.vars.pauseOnHover&&a.vars.slideshow&&a.hover((function(){a.pause()}),(function(){a.resume()})),a.animatedSlides=setInterval(a.animateSlides,a.vars.slideshowSpeed)),a.vars.pausePlay){var r=e('<div class="flex-pauseplay"><span></span></div>');a.containerExists?(a.controlsContainer.append(r),a.pausePlay=e(".flex-pauseplay span",a.controlsContainer)):(a.append(r),a.pausePlay=e(".flex-pauseplay span",a));var l=a.vars.slideshow?"pause":"play";a.pausePlay.addClass(l).text(l),a.pausePlay.click((function(n){n.preventDefault(),e(this).hasClass("pause")?a.pause():a.resume()}))}a.vars.touchSwipe&&"ontouchstart"in document.documentElement&&a.each((function(){var e,n=20;function t(i){if(isMoving){var s=i.touches[0].pageX,o=e-s;if(Math.abs(o)>=n){!function(){this.removeEventListener("touchmove",t),e=null,isMoving=!1}();var r=o>0?a.getTarget("next"):a.getTarget("prev");a.canAdvance(r)&&a.flexAnimate(r,a.vars.pauseOnAction)}}}isMoving=!1,"ontouchstart"in document.documentElement&&this.addEventListener("touchstart",(function(n){1==n.touches.length&&(e=n.touches[0].pageX,isMoving=!0,this.addEventListener("touchmove",t,!1))}),!1)})),"slide"==a.vars.animation.toLowerCase()&&(a.sliderTimer,e(window).resize((function(){a.newSlides.width(a.width()),a.container.width((a.count+a.cloneCount)*a.width()+2e3),clearTimeout(a.sliderTimer),a.sliderTimer=setTimeout((function(){a.flexAnimate(a.currentSlide)}),300)}))),a.vars.start(a)},a.flexAnimate=function(e,n){a.animating||(a.animating=!0,n&&a.pause(),a.vars.controlNav&&a.controlNav.removeClass("active").eq(e).addClass("active"),a.atEnd=0==e||e==a.count-1,a.vars.animationLoop||(0==e?a.directionNav.removeClass("disabled").filter(".prev").addClass("disabled"):e==a.count-1?(a.directionNav.removeClass("disabled").filter(".next").addClass("disabled"),a.pause(),a.vars.end(a)):a.directionNav.removeClass("disabled")),a.animatingTo=e,a.vars.before(a),"slide"==a.vars.animation.toLowerCase()?(0==a.currentSlide&&e==a.count-1&&a.vars.animationLoop?a.slideString="0px":a.currentSlide==a.count-1&&0==e&&a.vars.animationLoop?a.slideString=-1*(a.count+1)*a.slides.filter(":first").width()+"px":a.slideString=-1*(e+a.cloneOffset)*a.slides.filter(":first").width()+"px",a.container.animate({marginLeft:a.slideString},a.vars.animationDuration,(function(){0==a.currentSlide&&e==a.count-1&&a.vars.animationLoop?a.container.css({marginLeft:-1*a.count*a.slides.filter(":first").width()+"px"}):a.currentSlide==a.count-1&&0==e&&a.vars.animationLoop&&a.container.css({marginLeft:-1*a.slides.filter(":first").width()+"px"}),a.animating=!1,a.currentSlide=e,a.vars.after(a)}))):(a.slides.eq(a.currentSlide).fadeOut(a.vars.animationDuration),a.slides.eq(e).fadeIn(a.vars.animationDuration,(function(){a.animating=!1,a.currentSlide=e,a.vars.after(a)}))))},a.animateSlides=function(){if(!a.animating){var e=a.currentSlide==a.count-1?0:a.currentSlide+1;a.flexAnimate(e)}},a.pause=function(){clearInterval(a.animatedSlides),a.vars.pausePlay&&a.pausePlay.removeClass("pause").addClass("play").text("play")},a.resume=function(){a.animatedSlides=setInterval(a.animateSlides,a.vars.slideshowSpeed),a.vars.pausePlay&&a.pausePlay.removeClass("play").addClass("pause").text("pause")},a.canAdvance=function(e){return!(!a.vars.animationLoop&&a.atEnd)||(0!=a.currentSlide||e!=a.count-1||"next"==a.direction)&&(a.currentSlide!=a.count-1||0!=e||"next"!=a.direction)},a.getTarget=function(e){return a.direction=e,"next"==e?a.currentSlide==a.count-1?0:a.currentSlide+1:0==a.currentSlide?a.count-1:a.currentSlide-1},a.init()},e.flexslider.defaults={animation:"fade",slideshow:!0,slideshowSpeed:7e3,animationDuration:600,directionNav:!0,controlNav:!0,keyboardNav:!0,touchSwipe:!0,prevText:"Previous",nextText:"Next",pausePlay:!1,randomize:!1,slideToStart:0,animationLoop:!0,pauseOnAction:!0,pauseOnHover:!1,controlsContainer:"",manualControls:"",start:function(){},before:function(){},after:function(){},end:function(){}},e.fn.flexslider=function(n){return this.each((function(){1==e(this).find(".slides li").length?e(this).find(".slides li").fadeIn(400):1!=e(this).data("flexslider")&&new e.flexslider(e(this),n)}))}}(jQuery);