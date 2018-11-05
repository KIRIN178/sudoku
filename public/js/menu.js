$(document).ready(function () {
    init();
})
function init(
) {
    $('#data_link').click(function () {
        $(this).hide();
        $('#data_link_block').show();
    })
    $('#create').click(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: $('#form_create').attr('action'),
            data: $("#form_create").serialize(),
            success: function (msg) {
                location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    })
    $('#delete').click(function () {
        $.ajax({
            type: "DELETE",
            dataType: 'json',
            url: '/menu/question',
            data: $("#form_play").serialize(),
            success: function (msg) {
                location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    })
    $('#inherit').click(function () {
        $.ajax({
            type: "PUT",
            dataType: 'json',
            url: $('#form_inherit').attr('action'),
            data: $("#form_inherit").serialize(),
            success: function (msg) {
                location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    })
    $('#play').click(function () {
        window.location.href = "/play/"+$('#question_list').children(':selected').attr('data-id');
    })
}