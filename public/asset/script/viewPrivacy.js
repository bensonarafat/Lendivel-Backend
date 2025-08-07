$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".privacySideA").addClass("activeLi");

    let summernoteOptions = {
        height: 550,
    };
    $("#summernote").summernote(summernoteOptions);

    $("#privacy").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#privacy")[0]);
            $.ajax({
                url: domainUrl + "updatePrivacy",
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    // $(".loader").hide();
                    location.reload();
                },
                error: (error) => {
                    $(".loader").hide();
                    console.log(JSON.stringify(error));
                },
            });
        } else {
            $(".loader").hide();
            iziToast.error({
                title: "Error!",
                message: " you are Tester ",
                position: "topRight",
            });
        }
    });
});
