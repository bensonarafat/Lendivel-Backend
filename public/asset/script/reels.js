$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".reelsSideA").addClass("activeLi");

    $("#reelsTable").on("click", ".view-content", function (event) {
        event.preventDefault();
        var contentUrl = $(this).data("url");
        var description = $(this).data("description");

        $("#videoDesc").text(description);
        $("#video source").attr("src", contentUrl);
        $("#video")[0].load();
        $("#video_modal").modal("show");
        $("#video").trigger("play");
    });

    $("#video_modal").on("hidden.bs.modal", function () {
        $("#video").trigger("pause");
    });

    $("#reelsTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchAllReelsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#reelsTable").on("click", ".delete", function (event) {
        event.preventDefault();
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                if (user_type == "1") {
                    var id = $(this).attr("rel");
                    var url = `${domainUrl}deleteReelAdmin` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#reelsTable").DataTable().ajax.reload(null, false);
                        iziToast.success({
                            title: strings.success,
                            message: strings.operationSuccessful,
                            position: "topRight",
                        });
                    });
                } else {
                    iziToast.error({
                        title: strings.error,
                        message: strings.youAreTester,
                        position: "topRight",
                    });
                }
            }
        });
    });
});
