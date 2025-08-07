$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".couponsSideA").addClass("activeLi");

    $("#couponsTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchAllCouponsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#addCouponForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#addCouponForm")[0]);
            $.ajax({
                url: `${domainUrl}addCouponItem`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#addCouponModal").modal("hide");
                    $("#addCouponForm").trigger("reset");
                    $("#couponsTable").DataTable().ajax.reload(null, false);
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                },
                error: (error) => {
                    $(".loader").hide();
                    console.log(JSON.stringify(error));
                },
            });
        } else {
            $(".loader").hide();
            iziToast.error({
                title: strings.error,
                message: strings.youAreTester,
                position: "topRight",
            });
        }
    });
    $("#editCouponForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#editCouponForm")[0]);
            $.ajax({
                url: `${domainUrl}editCouponItem`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#editCouponModal").modal("hide");
                    $("#editCouponForm").trigger("reset");
                    $("#couponsTable").DataTable().ajax.reload(null, false);
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                },
                error: (error) => {
                    $(".loader").hide();
                    console.log(JSON.stringify(error));
                },
            });
        } else {
            $(".loader").hide();
            iziToast.error({
                title: strings.error,
                message: strings.youAreTester,
                position: "topRight",
            });
        }
    });

    $("#couponsTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var maxDiscAmount = $(this).data("maxdiscamount");
        var percentage = $(this).data("percentage");
        var coupon = $(this).data("coupon");
        var description = $(this).data("description");
        var heading = $(this).data("heading");
        var percentage = $(this).data("percentage");
        var id = $(this).attr("rel");

        $("#editCouponId").val(id);
        $("#editMaxDiscAmount").val(maxDiscAmount);
        $("#editCoupon").val(coupon);
        $("#editHeading").val(heading);
        $("#editDescription").val(description);
        $("#editPercentage").val(percentage);

        $("#editCouponModal").modal("show");
    });
    $("#couponsTable").on("click", ".delete", function (event) {
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
                    var url = `${domainUrl}deleteCoupon` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#couponsTable").DataTable().ajax.reload(null, false);
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
