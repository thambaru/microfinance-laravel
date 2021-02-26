$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
});

function triggerDeleteForm(event, formId) {
    event.preventDefault();

    if (confirm("Are you sure want to delete?"))
        document.getElementById(formId).submit();
}

$('[confirm-dialog]').click(function (event) {
    event.preventDefault();

    if (confirm("Are you sure want to proceed?"))
        window.location.href = $(this).attr('href');
})