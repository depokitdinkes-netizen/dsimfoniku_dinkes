$(document).ready(function (e) {
    $("#open-sidebar").click(function (e) {
        $("#sidebar").removeClass("-translate-x-full");
        $(this).addClass("hidden");
    });

    $("#close-sidebar").click(function (e) {
        $("#sidebar").addClass("-translate-x-full");
        $("#open-sidebar").removeClass("hidden");
    });
});
