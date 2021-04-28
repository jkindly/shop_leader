$(document).ready(function() {
    $('tr[data-href]').on("click", function() {
        document.location = $(this).data('href');
    });

    $('#user-nav-avatar').on('click', function() {
        $('#user-nav-avatar-menu').toggle();
    });

    $('body').not('#user-nav-avatar').on('click', function(e) {
        if ($(e.target).attr('id') === 'user-nav-avatar')
            return false;

        $('#user-nav-avatar-menu').hide();
    });
});
