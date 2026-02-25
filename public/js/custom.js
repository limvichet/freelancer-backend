$('#menu_toggle').on('click', function () {
    $('.left_col').toggleClass('hide-menu');
    $('.right_col').toggleClass('full-width');
});

$(document).on("click", ".side-menu li a", function (e) {
    e.preventDefault();

    // Reset all icons to "chevron-right"
    $(".side-menu li a span.fa")
        .removeClass("fa-chevron-down")
        .addClass("fa-chevron-right");

    let $icon = $(this).find("span.fa");

    // Fade out, toggle this one only
    $icon.fadeOut(250, function () {
        if ($icon.hasClass("fa-chevron-right")) {
            $icon.removeClass("fa-chevron-right").addClass("fa-chevron-down");
        } else {
            $icon.removeClass("fa-chevron-down").addClass("fa-chevron-right");
        }
        $icon.fadeIn(250);
    });
});




