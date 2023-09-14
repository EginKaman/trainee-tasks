$(document).ready(function () {
    if ($('input[type="radio"][name="type"]:checked').val() === 'cron') {
        $('#type-cron').show();
    }
    if ($('input[type="checkbox"][name="is_loop"]').prop('checked')) {
        $('#loop-active').show();
    }
    $('input[type="radio"][name="type"]').on('change', function () {
        let inputValue = $(this).attr("value");
        let targetBox = $("#type-" + inputValue);
        $(".settings").not(targetBox).hide();
        $(targetBox).show();
    });
    $('input[type="checkbox"][name="is_loop"]').on('change', function () {
        console.log($(this).prop('checked'));
        let checked = $(this).prop('checked');
        if (checked) {
            $('#loop-active').show();
        } else {
            $('#loop-active').hide();
        }
    });
});
