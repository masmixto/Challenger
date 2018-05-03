/**
 * Created by masmiix on 19.01.18.
 */
$(document).ready(function () {
    $('.js-acceptFriendRequest').on('click', function (e)
    {
        e.preventDefault(); //zadnych automatycznych zachowan przegladarki

        accFriendRequest($(this).closest('tr'),$(this).attr('val')); //(thisElement, id)

    });
});



function accFriendRequest(element, id)
{
    var accFriendRequest = acceptFriendRequest.replace('elementId', id);
    $.ajax
    ({
        url: accFriendRequest,
        type: 'POST',
        async: true
    }).success(function(response) {
        jQuery(thisElement).fadeOut("normal");
        $('#overlay').css('display','block');
        $('#modal').css('display','block');
        $('#content').text('You are now friends');
    }).fail(function(){
        $('#overlay').css('display','block');
        $('#modal').css('display','block');
        $('#content').text('You will no be friends');
    });
}
