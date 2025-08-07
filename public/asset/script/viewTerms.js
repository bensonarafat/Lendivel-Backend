$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".termsSideA").addClass("activeLi");

    let summernoteOptions = {
        height: 550,
    };
    $("#summernote").summernote(summernoteOptions);

    $("#terms").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#terms")[0]);
            $.ajax({
                url: domainUrl + "updateTerms",
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
