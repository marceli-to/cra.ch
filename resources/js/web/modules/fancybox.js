var FancyBox = (function() {

  var selectors = {
    body: 'body',
    gallery: '[data-fancybox="gallery"]',
  };

  var _initialize = function() {
    $(selectors.gallery).fancybox({
      buttons: [
        "close"
      ],
      infobar: false,

      btnTpl: {
        close: '<a href="javascript:;" data-fancybox-close class="btn-fancybox-close"></a>',
        arrowLeft: '<a href="javascript:;" data-fancybox-prev class="btn-fancybox-prev"><span></span></a>',
        arrowRight: '<a href="javascript:;" data-fancybox-next class="btn-fancybox-next"><span></span></a>',
      },

      animationEffect: "fade",
      animationDuration: 366,
      transitionDuration: 600,
      clickContent: false,

      baseTpl:
        '<div class="fancybox-container" role="dialog" tabindex="-1">' +
          '<div class="fancybox-bg"></div>' +
          '<div class="fancybox-inner">' +
            '<div class="fancybox-infobar"><span data-fancybox-index></span>&nbsp;/&nbsp;<span data-fancybox-count></span></div>' +
            '<div class="fancybox-toolbar">{{buttons}}</div>' +
            '<div class="fancybox-navigation">{{arrows}}</div>' +
            '<div class="fancybox-stage"></div>' +
            '<div class="fancybox-caption"><div class=""fancybox-caption__body"></div></div>' +
          '</div>' +
        '</div>',
      
      afterLoad: function(fb, item){
        item.$content.find('[data-fancybox-index]').html(item.index+1);
        item.$content.find('[data-fancybox-count]').html(fb.group.length);
      },

      afterShow: function(instance, current) {
        $('.btn-fancybox-next').removeClass('is-inactive');
        $('.btn-fancybox-prev').removeClass('is-inactive');

        if (current.index  ===  instance.group.length - 1) {
          $('.btn-fancybox-next').addClass('is-inactive');
        }
        if (current.index  ===  0) {
          $('.btn-fancybox-prev').addClass('is-inactive');
        }
      },

      caption: function(instance, item ) {
        var caption = $(this).data('caption') || '';
        return '<div><div>' + ( caption.length ? caption : '' ) + '</div><span data-fancybox-index></span>/<span data-fancybox-count></span></div>';
      }
    });
  };

  return {
    init: _initialize,
  };

})();

// Initialize
$(function() {
  FancyBox.init();
});