$(document).ready(() => {
    $(".close-alert").click((e) => {
        $(".alert").addClass("hidden");
    });

    // Automatically hide the alert after 2 seconds
    setTimeout(() => {
        $(".alert").addClass("hidden");
    }, 2000);
});
