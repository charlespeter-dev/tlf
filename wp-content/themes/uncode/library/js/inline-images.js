(function($) {
	"use strict";

	UNCODE.inlineImgs = function() {
    var linkInlineImgs = function(){
        $('.un-inline-image').each(function(){
            var $img = $(this),
                href = $(this).attr('data-link-href'),
                dataImg = $img.data();
			if ( dataImg != '' && href != null && href !== '' ) {
                var class_stra_a = 'unline-image-link';
                if ( $(this).hasClass('un-inline-space-over') ) {
                    class_stra_a += ' un-inline-space-over';
                } else if ( $(this).hasClass('un-inline-space-gutter') ) {
                    class_stra_a += ' un-inline-space-gutter';
                }
                var str_a = '<a class="' + class_stra_a + '" ';
                for (var key in dataImg) {
                    if (dataImg.hasOwnProperty(key) && key.startsWith("link")) {
                        str_a += " " + key.slice(4).toLowerCase() + "=\"" + dataImg[key] + "\"";
                    }
                }
                str_a += ' />';
                $img.wrap(str_a);
			}
        });
    };
    $(window).on('load', linkInlineImgs );
};

})(jQuery);
