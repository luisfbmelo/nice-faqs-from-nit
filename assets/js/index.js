(function($){
    /**
     * FAQs
     */

    $('.faqs-faq-toggle').on('click', function(e){
        e.stopPropagation();
        var targetEl = $("#faq-body-"+$(this).data("postid"));
        targetEl.slideToggle(300);
        targetEl.toggleClass('active');
        $(this).toggleClass('active');
    });

    $('.faqs-faq-category-title').on('click',  function(e){
        e.stopPropagation();
        var targetEl = $(this).nextAll('.faqs-list');
        targetEl.slideToggle(300);
        targetEl.toggleClass('active');
        $(this).toggleClass('active');
    });

    $('.faqs-category-toggle').on('click',  function(e){
        e.stopPropagation();
        var targetEl = $(this).find('.faqs-category');
        targetEl.slideToggle(300);
        targetEl.toggleClass('active');
        $(this).toggleClass('active');
    });
})(jQuery);
console.log("BOOM");