
document.addEventListener("DOMContentLoaded", function () {
    const config = {
        threshold: 0.5
    };
    var lazyImages = [].slice.call( document.querySelectorAll(".lazy, [data-lazy='true']") );

    if ("IntersectionObserver" in window) {
        let lazyImageObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                    lazyImage.src = lazyImage.dataset.src;
                    lazyImage.removeAttribute("data-src");
                    lazyImage.classList.add("loaded");
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        }, config);
        lazyImages.forEach(function (lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    } else {
        // Fallback for browsers that do not support IntersectionObserver
    }
});


(function($){
	/* All Images Loaded */
	$(window).on('load', function(){   
        var document_width = $(document).width();

        // Sticky elements
        if( $('#header').hasClass('enable-sticky') ){
            var header_height = $('#header div.menu-wrapper').outerHeight();
            $('#header').height( header_height );
        }

        $(window).on('resize', function() {
            if( $('#header').hasClass('enable-sticky') ){
                var header_height = $('#header div.menu-wrapper').outerHeight();
                $('#header').height( header_height );
            }
        });

        var referenceElement = document.getElementById('header');
        var header = document.getElementById('header');
        var backToTopButton = $('#back-to-top');
        
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (!entry.isIntersecting) {
                    backToTopButton.addClass('visible');
                    if( $('#header').hasClass('enable-sticky') ){
                        header.toggleAttribute('data-stuck', true);
                    }                    
                } else {
                    backToTopButton.removeClass('visible');
                    header.toggleAttribute('data-stuck', false);
                }
            });
        }, {
            threshold: [1],
            rootMargin: '0px 0px 0px 0px'
        });
        
        observer.observe(referenceElement);

        // Masonry
        if($(document).width() > 767){
            var $grid = $('.enable-masonry .grid-posts').masonry({
                itemSelector: 'article',
                gutter: 0,
                horizontalOrder: true
            });
            setTimeout(function(){
                $grid.masonry('layout');
            }, 500);
        } 

	});
	/* Dom Loaded */
	$(document).ready(function($){

        $('[data-aos]').addClass('aos-animate');

        if( $('.epcl-single-toc').length > 0 ){
            $('.epcl-single-toc .toggle-title').on('click', function(){
                $(this).next().stop().slideToggle();
                $(this).parent().toggleClass('active');
            });

            $('.main-article div.text').find('h1, h2, h3, h4, h5, h6').each(function(index){
                if( !$(this).attr('id') ){
                    // var id = $(this).text().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                    // var id =  $(this).text().toLowerCase().replace(/ /g, '-'); 
                    var id = Diacritics.clean( $(this).text().toLowerCase() ).replace(/ /g, '-');
                    $(this).attr('id', id);
                }
            });
            var heading_selector = $('.epcl-single-toc').data('heading-selector'); 
            if( !heading_selector ){
                heading_selector = 'h1, h2, h3, h4, h5';
            }
            tocbot.init({
                // Where to render the table of contents.
                tocSelector: '.epcl-single-toc .epcl-toc',
                // Where to grab the headings to build the table of contents.
                contentSelector: 'article div.text',
                // Which headings to grab inside of the contentSelector element.
                headingSelector: heading_selector,
                linkClass: 'epcl-toc-link',
                collapseDepth: 6,
                // Ensure correct positioning
                // hasInnerContainers: false,
                headingsOffset: 100,
                scrollSmoothOffset: -100,    
                activeListItemClass: 'epcl-is-active-li',
                activeLinkClass: 'epcl-is-active-link',
            });
        }

        if( $('.widget_epcl_toc').length > 0 ){
            $('article div.text').find('h1, h2, h3, h4, h5').each(function(index){
                if( !$(this).attr('id') ){
                    var id = $(this).text().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                    $(this).attr('id', id);
                }
            });
            var widget_heading_selector = $('.widget_epcl_toc .toc').data('heading-selector'); 
            if( !widget_heading_selector ){
                widget_heading_selector = 'h1, h2, h3, h4, h5';
            }
            tocbot.init({
                // Where to render the table of contents.
                tocSelector: '.widget_epcl_toc .toc',
                // Where to grab the headings to build the table of contents.
                contentSelector: 'article div.text',
                // Which headings to grab inside of the contentSelector element.
                headingSelector: widget_heading_selector,
                // Ensure correct positioning
                hasInnerContainers: false,
                headingsOffset: 100,
                scrollSmoothOffset: -100,    
            });
        }

        $('.widget_epcl_topics_index .toggle-icon').on('click', function(){
            var parent = $(this).parents('.item');
            parent.toggleClass('open');
            parent.find('.post-list').stop().slideToggle();
        });

        // Enable HTML5 form validation
        $('#commentform').removeAttr('novalidate');

        // Single Post copy button
        
        var copy_button = $(".permalink .copy");
        copy_button.on('click', function(){
            $("#copy-link").select();
            var originalText = copy_button.text();
            document.execCommand('copy');
            copy_button.text( copy_button.data('copied') );
            setTimeout(function() {
                copy_button.text(originalText);
                
            }, 3000);           
        });

        // Ajax Views counter
        if( $('body').hasClass('single-post') && $('#single').data('post-id') ){
            var post_id = parseInt( $('#single').data('post-id') );
            $.ajax({
                type: 'post',
                url: ajax_var.url,
                data: { action: 'epcl_views_counter', nonce: ajax_var.nonce, post_id: post_id },
                success: function(count){

                }
            });
        }

        // Lightbox

        var mfp_close_markup = '<span title="%title%" class="mfp-close">&times;</span>';
        var mfp_arrow_markup = '<span class="mfp-arrow mfp-arrow-%dir%"><svg class="icon ularge"><use xlink:href="'+ajax_var.assets_folder+'/images/svg-icons.svg#%dir%-arrow"></use></svg></span>';

        $('.lightbox').magnificPopup({
			mainClass: 'my-mfp-zoom-in',
			removalDelay: 300,
			closeMarkup: mfp_close_markup,
			fixedContentPos: true
        });

        $('.main-nav .lightbox, .search-menu-item a, .epcl-search-button').magnificPopup({
			mainClass: 'my-mfp-zoom-in box-bg-color',
			removalDelay: 300,
			closeMarkup: mfp_close_markup,
            fixedContentPos: true,
            closeBtnInside: false,
            callbacks: {
                beforeOpen: function(item) {
                    setTimeout(function() { $('#search-lightbox form #s').focus() }, 500);
                },
            },
        });

        // Global: related galleries

        if( !$('body').hasClass('epcl-disable-lightbox') ){
            $('.epcl-gallery').each(function() {
                var elem = $(this);
                elem.find('ul').magnificPopup({
                    type: 'image',
                    gallery:{
                        enabled: true,
                        arrowMarkup: mfp_arrow_markup,
                        tCounter: '%curr% / %total%'
                    },
                    delegate: 'a',
                    mainClass: 'my-mfp-zoom-in',
                    removalDelay: 300,
                    closeMarkup: mfp_close_markup
                });
            });

            // Gutenberg Gallery with lightbox
            $('.wp-block-gallery, .widget_media_gallery, .woocommerce-product-gallery').each(function() {
                var elem = $(this);
                elem.magnificPopup({
                    type: 'image',
                    gallery:{
                        enabled: true,
                        arrowMarkup: mfp_arrow_markup,
                        tCounter: '%curr% / %total%'
                    },
                    delegate: "a[href*='.jpg'],a[href*='.png'],a[href*='.gif'],a[href*='.jpeg'],a[href*='.webp']",
                    mainClass: 'my-mfp-zoom-in',
                    removalDelay: 300,
                    closeMarkup: mfp_close_markup,
                    image: {
                        titleSrc: function(item) {
                            return item.el.parent().find('figcaption').text();
                        }
                    }
                });
            });

            // Gutenberg Single Image with lightbox
            $(".wp-block-image").not('.wp-block-gallery .wp-block-image').magnificPopup({
                type: 'image',
                gallery:{
                    enabled: false,
                    arrowMarkup: mfp_arrow_markup,
                    tCounter: '%curr% / %total%'
                },
                delegate: "a[href*='.jpg'],a[href*='.png'],a[href*='.gif'],a[href*='.jpeg'],a[href*='.webp']",
                mainClass: 'my-mfp-zoom-in',
                removalDelay: 300,
                closeMarkup: mfp_close_markup,
                image: {
                    titleSrc: function(item) {
                        return item.el.parent().find('figcaption').text();
                    }
                }
            });
        }

        // Custom Ajax Scripts
        if( $('#epcl-ajax-scripts').length > 0){
            $('#epcl-ajax-scripts > div').each(function( index ) {
                var script_src = $(this).data('src');
                var script_cache = parseInt( $(this).data('cache') );
                if ( script_cache == 0 ) script_cache = false;
                else script_cache = true;
                var script_timeout = parseInt( $(this).data('timeout') );

                if( script_timeout > 0 ){
                    setTimeout( function(){
                        $.ajax({
                            url: script_src,
                            dataType: 'script',
                            async: true,
                            cache: script_cache
                        });
                    }, script_timeout );
                }else{
                    $.ajax({
                        url: script_src,
                        dataType: 'script',
                        async: true,
                        cache: script_cache
                    });
                }

            });
        }

		/* Global */

		// Side and Mobile menu       
        $('#header div.menu-mobile, #header .open-menu').on('click', function(){
			$('html').toggleClass('epcl-menu-open');
        });
        $('.menu-overlay, .side-nav .close').on('click', function(){
			$('html').removeClass('epcl-menu-open');
        });
        $('nav.mobile li.menu-item-has-children > a').on('click', function(e){
			$(this).parent().toggleClass('open');
            e.preventDefault();
        });

		$('#back-to-top').click(function(event) {
			event.preventDefault();
			$('html, body').animate({scrollTop: 0}, 500);
			return false;
		});

        // Gallery Post Format

        $('.post-format-gallery .slick-slider').each(function(){
            var rtl = false;
            if( parseInt( $(this).data('rtl') ) > 0 ){
                rtl = true;
            }
            $(this).slick({
                cssEase: 'ease',
                fade: true,
                arrows: true,
                infinite: true,
                dots: false,
                autoplay: false,
                speed: 600,
                slidesToShow: 1,
                slidesToScroll: 1,
                rtl: rtl,
            });
        });

        // Module: Featured Articles
        $('.epcl-post-carousel .slick-slider').each(function(index, el) {
            var slides_to_show = parseInt( $(this).data('show') );
            var rtl = false;
            if( parseInt( $(this).data('rtl') ) > 0 ){
                rtl = true;
            }            
            $(this).slick({
                cssEase: 'ease',
                fade: false,
                arrows: false,
                infinite: true,
                dots: true,
                autoplay: false,
                speed: 600,
                rtl: rtl,
                slidesToShow: slides_to_show,
                slidesToScroll: slides_to_show,
                responsive: [,
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 980,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                ]
            });
        });

        // Demo
        $('.epcl-demo-tool a').on('click', function(e){
            var body_class = $(this).data('class');
            $('body').toggleClass( body_class );
            $(this).toggleClass('active');
            if( $('body').hasClass('disable-custom-colors') ){
                $('.epcl-category-overlay').hide();
            }else{
                $('.epcl-category-overlay').show();
            }
            if( $('body').hasClass('disable-decorations') ){
                $('.epcl-waves-page').hide();
            }else{
                $('.epcl-waves-page').show();
            }
            e.preventDefault();
        });
        $(' .epcl-demo-tool input[type=color]').on('input', function(e){
            var value = e.target.value;
            var data_class = $(this).data('class');
            var data_target = String( $(this).data('target') );
            var data_attr = String( $(this).data('attr') );
            if( data_class !== 'undefined' && data_attr !== 'undefined') {
                $(data_class).css(data_attr, value);
            } else {
                $(":root").css({
                    [data_target]: value
                });                
            }    
        });

	});

    // Demo
    $('.epcl-demo-tool .link').on('click', function(e){
        var body_class = $(this).data('class');
        $('body').toggleClass( body_class );
        $(this).toggleClass('active');
        if( $('body').hasClass('disable-custom-colors') ){
            $('.epcl-category-overlay').hide();
        }else{
            $('.epcl-category-overlay').show();
        }
        if( $('body').hasClass('disable-decorations') ){
            $('.epcl-waves-page').hide();
        }else{
            $('.epcl-waves-page').show();
        }
        e.preventDefault();
    });
    $(' .epcl-demo-tool input[type=color]').on('input', function(e){
        var value = e.target.value;
        var data_class = $(this).data('class');
        var data_target = String( $(this).data('target') );
        var data_attr = String( $(this).data('attr') );
        if( data_class !== 'undefined' && data_attr !== 'undefined') {
            $(data_class).css(data_attr, value);
        } else {
            $(":root").css({
                [data_target]: value
            });                
        }    
    });

})(jQuery);

(function() {
    var supportsPassive = eventListenerOptionsSupported();  

    if (supportsPassive) {
      var addEvent = EventTarget.prototype.addEventListener;
      overwriteAddEvent(addEvent);
    }

    function overwriteAddEvent(superMethod) {
      var defaultOptions = {
        passive: true,
        capture: false
      };

      EventTarget.prototype.addEventListener = function(type, listener, options) {
        var usesListenerOptions = typeof options === 'object';
        var useCapture = usesListenerOptions ? options.capture : options;
        options = usesListenerOptions ? options : {};
        if( type == 'touchstart' || type == 'touchmove'){
            options.passive = options.passive !== undefined ? options.passive : defaultOptions.passive;
        }        
        options.capture = useCapture !== undefined ? useCapture : defaultOptions.capture;

        superMethod.call(this, type, listener, options);
      };
    }

    function eventListenerOptionsSupported() {
      var supported = false;
      try {
        var opts = Object.defineProperty({}, 'passive', {
          get: function() {
            supported = true;
          }
        });
        window.addEventListener("test", null, opts);
      } catch (e) {}

      return supported;
    }

  })();

