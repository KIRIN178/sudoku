$(document).ready(function () {
    init();
})
function init(
) {
    $('.sudoku_board input').keydown(function (e) {
        if ((e.keyCode >= 49 && e.keyCode <= 57) || (e.keyCode >= 97 && e.keyCode <= 105)) {
            if ($(this).val().length > 0) {
                $(this).val('');
            }
        } else if (e.keyCode == 8) {
            $(this).val('');
        } else {
            e.preventDefault();
        }
    })
    $('#correction').click(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: $('#form_play').attr('action'),
            data: $("#form_play").serialize(),
            success: function (msg) {
                if (msg.status != 'ok') {
                    alert('error');
                    return;
                }
                $('#sudoku_container .sudoku_board input').each(function (i,ele) {
                    if (msg.correction[i] == 0) {
                        $(ele).addClass('incorrect');
                    } else if (msg.correction[i] == 1) {
                        $(ele).addClass('correct');
                    } else if (msg.correction[i] == 2) {
                        $(ele).removeClass('correct').removeClass('incorrect');
                    }
                })
                $('.legend').show();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    })
    $('#surrender').click(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: $('#form_play').attr('action').replace('correction','surrender'),
            data: $("#form_play").serialize(),
            success: function (msg) {
                if (msg.status != 'ok') {
                    alert('error');
                    return;
                }
                $('#sudoku_container .sudoku_board input').each(function (i,ele) {
                    var obj = $(ele).parent();
                    var text2 = '';
                    if (parseInt($(ele).val()) == parseInt(msg.correction[i])) {
                        var text = $('.text_correct').children().clone().text(msg.correction[i])
                    } else if ($(ele).val() == '') {
                        var text = $('.text_blank').children().clone().text(msg.correction[i])
                    } else {
                        var text = $('.text_delete').children().clone().text($(ele).val())
                        var text2 = $('.text_blank').children().clone().text(msg.correction[i])
                    }
                    $(obj).children().remove();
                    $(text).appendTo($(obj));
                    $(text2).appendTo($(obj));
                });
                $('#block_correction').hide();
                $('#block_surrender').hide();
                $('#block_final').show();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    })
}