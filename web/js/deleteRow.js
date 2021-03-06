$(document).ready(function () {
    $('.js-deleteRow').on('click', function (e)
    {
        e.preventDefault(); //zadnych automatycznych zachowan przegladarki

        deleteRow($(this).closest('tr'), $(this).attr('val')); //(thisElement, id)

    });
});



function deleteRow(thisElement, id)
{
    var remUrl = removeUrl.replace('elementId', id);
    $.ajax
    ({
        url: remUrl,
        type: 'DELETE',
        async: true
    }).success(function(response) {
        jQuery(thisElement).fadeOut("normal");
        $('#overlay').css('display','block');
        $('#modal').css('display','block');
        $('#content').text('Record deleted');
    }).fail(function(){
        $('#overlay').css('display','block');
        $('#modal').css('display','block');
        $('#content').text('Something went wrong');
    });
}
