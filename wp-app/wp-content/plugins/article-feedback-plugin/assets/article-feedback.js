jQuery(document).ready(function ($) {
    $('.article-vote-button').on('click', function () {
        var voteType = $(this).attr('id');

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'custom_vote_action',
                post_id: ajax_object.post_id, // Use the passed post_id
                vote_type: voteType,
            },
            success: function (response) {
                console.log(response.positive_percentage);

                $('.article-vote-button').prop('disabled', true);
                $('.question-title p').text('Thank you for your feedback.');
                $('#' + voteType).addClass('voted');
                $('#vote-yes').find('span').text(response.positive_percentage + '%');
                $('#vote-no').find('span').text(response.negative_percentage + '%');
            },
        });
    });
});