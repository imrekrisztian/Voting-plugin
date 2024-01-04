document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.article-vote-button').forEach(button => {
        button.addEventListener('click', function () {
            const voteType = this.getAttribute('id');

            fetch(ajax_object.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: new URLSearchParams({
                    'action': 'custom_vote_action',
                    'post_id': ajax_object.post_id, // Use the passed post_id
                    'vote_type': voteType,
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data.positive_percentage);

                    document.querySelectorAll('.article-vote-button').forEach(btn => {
                        btn.disabled = true;
                    });

                    document.querySelector('.question-title p').textContent = 'Thank you for your feedback.';
                    document.getElementById(voteType).classList.add('voted');

                    document.getElementById('vote-yes').querySelector('span').textContent = data.positive_percentage + '%';
                    document.getElementById('vote-no').querySelector('span').textContent = data.negative_percentage + '%';
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
