
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: false,
            maxWidth: 1800,
            maxHeight: 1200

            })
    });

