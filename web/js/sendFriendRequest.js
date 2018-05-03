/**
 * Created by masmiix on 19.01.18.
 */
$(document).ready(function () {
    $('.js-addFriend').on('click', function (e)
    {
        e.preventDefault(); //zadnych automatycznych zachowan przegladarki
        sendRequest($(this).attr('val'));
    });
});



function sendRequest(id)
{
    var sendRequest = sendFriendRequest.replace('elementId', id);
    $.ajax
    ({
        url: sendRequest,
        type: 'POST',
        async: true
    }).success(function(response) {
        $('#overlay').css('display','block');
        $('#modal').css('display','block');
        $('#content').text('Friend request sent');
    }).fail(function() {
        $('#overlay').css('display','block');
        $('#modal').css('display','block');
        $('#content').text('Friend request already sent');
    });
}
