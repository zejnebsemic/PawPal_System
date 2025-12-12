$(document).ready(function () {
    var app = $.spapp({
        defaultView: "home",
        templateDir: "pages/"
    });

    UserService.generateMenuItems();
    app.run();
});
