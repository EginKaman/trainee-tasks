$('form input, textarea').on('input', function () {
    let lengthBox = $('#length-' + this.name);
    if (lengthBox) {
        lengthBox.text(this.value.length);
    }
});
$("document").ready(function() {
    $('form input, textarea').trigger('input');
});
