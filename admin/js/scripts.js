(function($) {
    $(function() {
        try {
            $('input#wsvsba-title:disabled').css({cursor: 'default'});

            $('input#wsvsba-title').mouseover(function() {
                $(this).not('.focus').addClass('mouseover');
            });

            $('input#wsvsba-title').mouseout(function() {
                $(this).removeClass('mouseover');
            });

            $('input#wsvsba-title').focus(function() {
                $(this).addClass('focus').removeClass('mouseover');
            });

            $('input#wsvsba-title').blur(function() {
                $(this).removeClass('focus');
            });

            $('input#wsvsba-title').change(function() {
                updateTag();
            });

            updateTag();

        } catch (e) {
        }
    });

    function updateTag() {
        var title = $('input#wsvsba-title').val();

        if (title)
            title = title.replace(/["'\[\]]/g, '');

        $('input#wsvsba-title').val(title);
        var postId = $('input#post_ID').val();
        var tag = '[wizScriber id="' + postId + '" title="' + title + '"]';
        $('input#wizscriber-anchor-text').val(tag);
    }

})(jQuery);