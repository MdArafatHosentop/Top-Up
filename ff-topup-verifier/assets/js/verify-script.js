jQuery(document).ready(function($) {
    // আপনার থিমের প্লেয়ার আইডি ইনপুট বক্সের ID বা Class এখানে দিন
    $('#player_id_input').on('blur', function() {
        var playerID = $(this).val();
        var resultDiv = $('#verify-result');

        if(playerID.length > 5) {
            resultDiv.text('যাচাই করা হচ্ছে...');
            
            $.ajax({
                url: ff_ajax_obj.ajax_url,
                type: 'POST',
                data: {
                    action: 'verify_ff_id',
                    player_id: playerID
                },
                success: function(response) {
                    if(response.success) {
                        resultDiv.css('color', 'green').text('Name: ' + response.data);
                    } else {
                        resultDiv.css('color', 'red').text('আইডি পাওয়া যায়নি');
                    }
                }
            });
        }
    });
});
