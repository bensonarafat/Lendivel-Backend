var domainUrl ="http://127.0.0.1:8000/"; //"http://yourdomain.com/";
var sourceUrl = `${domainUrl}public/storage/`;

var strings = {
    error: "Error!",
    success: "Success!",
    youAreTester: "You Are Tester!",
    loginSuccessful: "Login Successful!",
    operationSuccessful: "Operation Successful!",
    doYouReallyWantToContinue: "Do you really want to continue?",
};

// add class on responsive

$(window).on("resize", function () {
    if ($(window).width() >= 1199) {
        $("table").removeClass("table-responsive");
    }

    if ($(window).width() <= 1199) {
        $("table").addClass("table-responsive");
    }
});
