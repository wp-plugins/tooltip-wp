<?php

/*
Plugin Name: Tooltip Wp
Plugin URI: http://themepoints.com
Description: This plugin will enable a awesome, nice, smooth tooltip for your website.install and enjoy this plugins feature.
Version: 1.1
Author: themepoints
Author URI: http://themepoints.com
License URI: http://themepoints.com/copyright/

*/




if ( ! defined( 'ABSPATH' ) ) exit;

/*===============================================
    Themepoints Tool Tip Plugin Path Register
=================================================*/
	
define('TP_TOOLTIP_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );



/*===============================================
    Register Latest jQuery/Css or Js Files
=================================================*/
function themepoints_tooltips_script()
	{
	wp_enqueue_script('jquery');
	wp_enqueue_style('tp-tool-tips-main-css', TP_TOOLTIP_PLUGIN_PATH.'css/tipso.css');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('tp-tool-tips-wp-color-picker', plugins_url(), array( 'wp-color-picker' ), false, true );
	}
add_action('init', 'themepoints_tooltips_script');


/*===============================================
    activate tool tip js
=================================================*/


function themepoints_main_js_customize(){
	 $themepoints_tooltip_speed = get_option( 'themepoints_tooltip_speed' );
			if(empty($themepoints_tooltip_speed))
				{
					$themepoints_tooltip_speed = "400";
				}	
	 $themepoints_tooltip_width = get_option( 'themepoints_tooltip_width' );
			if(empty($themepoints_tooltip_width))
				{
					$themepoints_tooltip_width = "200";
				}
	 $themepoints_tooltip_delay = get_option( 'themepoints_tooltip_delay' );
			if(empty($themepoints_tooltip_delay))
				{
					$themepoints_tooltip_delay = "200";
				}				
	 $themepoints_tooltip_color = get_option( 'themepoints_tooltip_color' );
			if(empty($themepoints_tooltip_color))
				{
					$themepoints_tooltip_color = "#fff";
				}				
	 $themepoints_tooltip_display = get_option( 'themepoints_tooltip_display' );			
				
	?>
    <script type="text/javascript">
    jQuery(document).ready(function($){



	var pluginName = "<?php echo $themepoints_tooltip_display; ?>",
		defaults = {
			speed       :<?php echo $themepoints_tooltip_speed; ?>,
            background  : '#55b555',
            color       : '<?php echo $themepoints_tooltip_color; ?>',
            position    : 'top',
            width       : <?php echo $themepoints_tooltip_width; ?>,
            delay       : <?php echo $themepoints_tooltip_delay; ?>,
            offsetX     : 0,
            offsetY     : 0,
            content     : null,
            useTitle    : true,
            onShow      : null,
            onHide      : null
	};

	function Plugin ( element, options ) {
			this.element = $(element);
			this.settings = $.extend({}, defaults, options);
			this._defaults = defaults;
			this._name = pluginName;
			this._title = this.element.attr('title');
			this.mode = 'hide';
			this.init();
	}

	$.extend(Plugin.prototype, {
		init: function () {
			var obj = this,
			$e = this.element;

			$e.addClass('tipso_style').removeAttr('title');

			if(isTouchSupported()){
				$e.on('click' + '.' + pluginName, function(e) {
					obj.mode == 'hide' ? obj.show() : obj.hide();
					e.stopPropagation();
				});
				$(document).on('click', function(){
					if(obj.mode == 'show'){
						obj.hide();
					}
				});
			} else {
				$e.on('mouseover' + '.' + pluginName, function() {
					obj.show();
				});
				$e.on('mouseout' + '.' + pluginName, function() {
					obj.hide();
				});
			}
		},
		tooltip: function () {
            if (!this.tipso_bubble) {
                this.tipso_bubble = $('<div class="tipso_bubble"><div class="tipso_content"></div><div class="tipso_arrow"></div></div>');
            }
            return this.tipso_bubble;
        },
		show: function () {
			var tipso_bubble = this.tooltip(),
            $e = this.element,
            obj = this, $win = $(window),
            arrow = 10,
            pos_top, pos_left;

        	tipso_bubble.css({
                background: obj.settings.background,
                color: obj.settings.color,
                width: obj.settings.width
            }).hide();
        	tipso_bubble.find('.tipso_content').html(obj.content());

        	reposition(obj);

        	$win.resize(function(){
        		reposition(obj);
        	});

			obj.timeout = window.setTimeout(function() {
                tipso_bubble.appendTo('body').stop(true, true).fadeIn( obj.settings.speed, function(){
                	obj.mode = 'show';
                    if ($.isFunction(obj.settings.onShow)){
                        obj.settings.onShow($(this));
                    }
                });
            }, obj.settings.delay);
		},
		hide: function () {
			var obj = this,
			tipso_bubble = this.tooltip();
			window.clearTimeout( obj.timeout );
			obj.timeout = null;

			tipso_bubble.stop(true, true).fadeOut( obj.settings.speed, function(){
				$(this).remove();
                if ($.isFunction(obj.settings.onHide) && obj.mode == 'show'){
                    obj.settings.onHide($(this));
                }
                obj.mode = 'hide';
            });
		},
		destroy: function () {
			$e = this.element;
	        $e.off('.' + pluginName);
			$e.removeData(pluginName);
			$e.removeClass('tipso_style').attr('title', this._title);
		},
		content: function () {
			var content,
            $e = this.element,
            obj = this,
            title = this._title;

            if(obj.settings.content){
                content = obj.settings.content;
            } else {
                if(obj.settings.useTitle == true){
                    content = title;
                } else {
                    content = $e.data('tipso');
                }
            }
            return content;
		},
		update: function (key, value) {
			var obj = this;
            if (value) {
                obj.settings[key] = value;
            } else {
                return obj.settings[key];
            }
        }
	});

    function isTouchSupported () {
        var msTouchEnabled = window.navigator.msMaxTouchPoints;
        var generalTouchEnabled = "ontouchstart" in document.createElement("div");


        if (msTouchEnabled || generalTouchEnabled) {
            return true;
        }
        return false;
    }
    function realHeight(obj){
        var clone = obj.clone();
        clone.css("visibility","hidden");
        $('body').append(clone);
        var height = clone.outerHeight();
        clone.remove();
        return height;
    }

    function reposition(thisthat){
        var tipso_bubble = thisthat.tooltip(),
            $e = thisthat.element,
            obj = thisthat, $win = $(window),
            arrow = 10,
            pos_top, pos_left;

        switch (obj.settings.position) {
            case 'top':
                pos_left = $e.offset().left + ( $e.outerWidth() / 2 ) - ( tipso_bubble.outerWidth() / 2 );
                pos_top  = $e.offset().top - realHeight(tipso_bubble) - arrow;
                tipso_bubble.find('.tipso_arrow').css({marginLeft: - 8});
                if( pos_top < $win.scrollTop() ){
                    pos_top  = $e.offset().top + $e.outerHeight() + arrow;
                    tipso_bubble.find('.tipso_arrow').css({'border-bottom-color': obj.settings.background, 'border-top-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass('bottom');
                } else {
                    tipso_bubble.find('.tipso_arrow').css({'border-top-color': obj.settings.background, 'border-bottom-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass('top');
                }
            break;
            case 'bottom':
                pos_left = $e.offset().left + ( $e.outerWidth() / 2 ) - ( tipso_bubble.outerWidth() / 2 );
                pos_top  = $e.offset().top + $e.outerHeight() + arrow;
                tipso_bubble.find('.tipso_arrow').css({marginLeft: - 8});
                if( pos_top + realHeight(tipso_bubble) > $win.scrollTop() + $win.outerHeight() ){
                    pos_top  = $e.offset().top - realHeight(tipso_bubble) - arrow;
                    tipso_bubble.find('.tipso_arrow').css({'border-top-color': obj.settings.background, 'border-bottom-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass('top');
                } else {
                    tipso_bubble.find('.tipso_arrow').css({'border-bottom-color': obj.settings.background, 'border-top-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass(obj.settings.position);
                }
            break;
            case 'left':
                pos_left = $e.offset().left - tipso_bubble.outerWidth() - arrow;
                pos_top  = $e.offset().top + ( $e.outerHeight() / 2 ) - ( realHeight(tipso_bubble) / 2);
                tipso_bubble.find('.tipso_arrow').css({marginTop: - 8, marginLeft:''});
                if( pos_left < $win.scrollLeft() ){
                    pos_left = $e.offset().left + $e.outerWidth() + arrow;
                    tipso_bubble.find('.tipso_arrow').css({'border-right-color': obj.settings.background, 'border-left-color': 'transparent', 'border-top-color': 'transparent', 'border-bottom-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass('right');
                } else {
                    tipso_bubble.find('.tipso_arrow').css({'border-left-color': obj.settings.background, 'border-right-color': 'transparent', 'border-top-color': 'transparent', 'border-bottom-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass(obj.settings.position);
                }
            break;
            case 'right':
                pos_left = $e.offset().left + $e.outerWidth() + arrow;
                pos_top  = $e.offset().top + ( $e.outerHeight() / 2 ) - ( realHeight(tipso_bubble) / 2);
                tipso_bubble.find('.tipso_arrow').css({marginTop: - 8, marginLeft:''});
                if( pos_left + arrow + obj.settings.width > $win.scrollLeft() + $win.outerWidth() ){
                    pos_left = $e.offset().left - tipso_bubble.outerWidth() - arrow;
                    tipso_bubble.find('.tipso_arrow').css({'border-left-color': obj.settings.background, 'border-right-color': 'transparent', 'border-top-color': 'transparent', 'border-bottom-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass('left');
                } else {
                    tipso_bubble.find('.tipso_arrow').css({'border-right-color': obj.settings.background, 'border-left-color': 'transparent', 'border-top-color': 'transparent', 'border-bottom-color': 'transparent'});
                    tipso_bubble.removeClass('top bottom left right');
                    tipso_bubble.addClass(obj.settings.position);
                }
            break;
        }
        if( pos_left < $win.scrollLeft() && ( obj.settings.position == 'bottom' || obj.settings.position == 'top' ) ){
            tipso_bubble.find('.tipso_arrow').css({marginLeft: pos_left - 8});
            pos_left = 0;
        }
        if( pos_left + obj.settings.width > $win.outerWidth() && ( obj.settings.position == 'bottom' || obj.settings.position == 'top' ) ){
            diff = $win.outerWidth() - ( pos_left + obj.settings.width );
            tipso_bubble.find('.tipso_arrow').css({marginLeft: - diff - 8, marginTop: '' });
            pos_left = pos_left + diff;
        }

        if( pos_left < $win.scrollLeft() && ( obj.settings.position == 'left' || obj.settings.position == 'right' ) ){
            pos_left = $e.offset().left + ( $e.outerWidth() / 2 ) - ( tipso_bubble.outerWidth() / 2 );
            tipso_bubble.find('.tipso_arrow').css({marginLeft: - 8, marginTop: ''});
            pos_top  = $e.offset().top - realHeight(tipso_bubble) - arrow;

            if( pos_top < $win.scrollTop() ){
                pos_top  = $e.offset().top + $e.outerHeight() + arrow;
                tipso_bubble.find('.tipso_arrow').css({'border-bottom-color': obj.settings.background, 'border-top-color': 'transparent', 'border-left-color': 'transparent', 'border-right-color': 'transparent'});
                tipso_bubble.removeClass('top bottom left right');
                tipso_bubble.addClass('bottom');
            } else {
                tipso_bubble.find('.tipso_arrow').css({'border-top-color': obj.settings.background, 'border-bottom-color': 'transparent', 'border-left-color': 'transparent', 'border-right-color': 'transparent'});
                tipso_bubble.removeClass('top bottom left right');
                tipso_bubble.addClass('top');
            }

            if( pos_left + obj.settings.width > $win.outerWidth() ){
                diff = $win.outerWidth() - ( pos_left + obj.settings.width );
                tipso_bubble.find('.tipso_arrow').css({marginLeft: - diff - 8, marginTop: '' });
                pos_left = pos_left + diff;
            }
            if( pos_left < $win.scrollLeft() ){
                tipso_bubble.find('.tipso_arrow').css({marginLeft: pos_left - 8});
                pos_left = 0;
            }
        }
        if( pos_left + obj.settings.width > $win.outerWidth() && ( obj.settings.position == 'left' || obj.settings.position == 'right' ) ){
            pos_left = $e.offset().left + ( $e.outerWidth() / 2 ) - ( tipso_bubble.outerWidth() / 2 );
            tipso_bubble.find('.tipso_arrow').css({marginLeft: - 8, marginTop: '' });
            pos_top  = $e.offset().top - realHeight(tipso_bubble) - arrow;

            if( pos_top < $win.scrollTop() ){
                pos_top  = $e.offset().top + $e.outerHeight() + arrow;
                tipso_bubble.find('.tipso_arrow').css({'border-bottom-color': obj.settings.background, 'border-top-color': 'transparent', 'border-left-color': 'transparent', 'border-right-color': 'transparent'});
                tipso_bubble.removeClass('top bottom left right');
                tipso_bubble.addClass('bottom');
            } else {
                tipso_bubble.find('.tipso_arrow').css({'border-top-color': obj.settings.background, 'border-bottom-color': 'transparent', 'border-left-color': 'transparent', 'border-right-color': 'transparent'});
                tipso_bubble.removeClass('top bottom left right');
                tipso_bubble.addClass('top');
            }
            if( pos_left + obj.settings.width > $win.outerWidth() ){
                diff = $win.outerWidth() - ( pos_left + obj.settings.width );
                tipso_bubble.find('.tipso_arrow').css({marginLeft: - diff - 8, marginTop: '' });
                pos_left = pos_left + diff;
            }
            if( pos_left < $win.scrollLeft() ){
                tipso_bubble.find('.tipso_arrow').css({marginLeft: pos_left - 8});
                pos_left = 0;
            }
        }

        tipso_bubble.css( { left: pos_left + obj.settings.offsetX, top: pos_top + obj.settings.offsetY} );
    }

	$[pluginName] = $.fn[pluginName] = function (options) {
	    var args = arguments;
	    if (options === undefined || typeof options === 'object') {
	        if (!(this instanceof $)) {
	            $.extend(defaults, options);
	        }
	        return this.each(function () {
	            if (!$.data(this, 'plugin_' + pluginName)) {
	                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
	            }
	        });
	    } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
	        var returns;
	        this.each(function () {
	            var instance = $.data(this, 'plugin_' + pluginName);
	            if (!instance) {
	                instance = $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
	            }
	            if (instance instanceof Plugin && typeof instance[options] === 'function') {
	                returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
	            }
	            if (options === 'destroy') {
	                $.data(this, 'plugin_' + pluginName, null);
	            }
	        });
	        return returns !== undefined ? returns : this;
	    }
	};

		
		})
    
    </script>
    <?php
	}
add_action('wp_head', 'themepoints_main_js_customize');



function themepoints_tool_tips_js_active(){	?>
<script type="text/javascript">
jQuery(document).ready(function(){
			// Position Tipso
		jQuery(document).ready(function(){
			// Position Tipso
			jQuery('.right').tipso({
				position: 'right',
				background: 'rgba(0,0,0,0.8)',
				useTitle: false,
			});
			jQuery('.left').tipso({
				position: 'left',
				background: 'tomato',
				useTitle: false,
			});
			jQuery('.bottom').tipso({
				position: 'bottom',
				background: '#2574A9',
				useTitle: false,
			});
			jQuery('.top, .destroy, .update, .update-tipso-content').tipso({
				position: 'top',
				background: '#F62459',
				useTitle: false,
			});
			// Use Title For Tipso Content
			jQuery('.title-tipso').tipso();
			// Tipso for Image
			jQuery('.img-tipso').tipso({
				useTitle : true,
				background: '#1abc9c'
			});
			// Show - Hide Tipso on Click
			jQuery('.show-hide').tipso({
				background: 'tomato',
				useTitle: false
			});
			jQuery('.show-hide-tipso').on('click', function(e){
				if(jQuery(this).hasClass('clicked')){
					jQuery(this).removeClass('clicked');
					jQuery('.show-hide').tipso('hide');
				} else {
					jQuery(this).addClass('clicked');
					jQuery('.show-hide').tipso('show');
				}
				e.preventDefault();
			});
			// Destroy Tipso
			jQuery('.destroy-tipso').on('click', function(e){
				jQuery('.destroy').tipso('destroy');
				e.preventDefault();
			});
			// Update Tipso Content
			jQuery('.update-tipso').on('click', function(e){
				jQuery('.update').tipso('update', 'content', 'this is updated tipso');
				e.preventDefault();
			});
			// Update Tipso Content from input field
			jQuery('.update-tipso-input').on('click', function(e){
				var content = jQuery('.tipso-content').val();
				jQuery('.update-tipso-content').tipso('update', 'content', content);
				e.preventDefault();
			});
			// Calback Tipso
			jQuery('.callback-tipso').tipso({
				onShow : function(){
			    	alert('Tipso Showed');
			  	},
				onHide: function(){
					alert('Tipso Hidden');
				}
			});
			jQuery('.page-load').tipso({
				position: 'left',
				background: 'rgb(236,236,236)',
				color: '#663399',
				useTitle: false

			});

		});
		jQuery(window).load(function(){
			// Show Tipso on Load
			jQuery('.page-load').tipso('show');
		});

		});
		
		
</script>
<?php	
	}

add_action('wp_head', 'themepoints_tool_tips_js_active');


/*===============================================
    activate tool tip main javascript
=================================================*/

function themepoints_tool_tips_main_js_activate(){?>
<script type="text/javascript">
	jQuery(document).ready(function($){

		$('.tipso').tipso();
	});
</script>
<?php	
	}

add_action('wp_head', 'themepoints_tool_tips_main_js_activate');


















/*===============================================
    insert admin page links
=================================================*/
function kh_settings_page(){
	include('admin-page.php');	
}

/*===============================================
    plugin option initialize
=================================================*/

function tfhemepoints_tooltip_init(){
        register_setting( 'themepoints_tooltip_plugin_options', '' );
    }	
add_action('admin_init', 'tfhemepoints_tooltip_init' );


/*===============================================
    add menu page item
=================================================*/

function themepoints_latest_tooltips_admin_page() {
	add_menu_page(__('Tp Tool Tip','themepoints'), __('Tool Tip','themepoints'), 'manage_options', 'themepointssetings', 'kh_settings_page');
}
add_action('admin_menu', 'themepoints_latest_tooltips_admin_page');




?>