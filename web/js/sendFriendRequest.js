/**
 * Created by masmiix on 19.01.18.
 */
$(document).ready(function () {
    $('.js-addFriend').on('click', function (e)
    {
        e.preventDefault(); //zadnych automatycznych zachowan przegladarki

        sendRequest($(this).attr('val')); //(thisElement, id)

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
        console.log('poszlo');
       // jQuery(thisElement).fadeOut("normal");
    });
}