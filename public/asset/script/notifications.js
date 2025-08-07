$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".notificationsSideA").addClass("activeLi");

    // Fetch Sound Categories
    var url = `${domainUrl}getFaqCats`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#usersTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchUserNotificationList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#doctorTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchDoctorNotificationList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#doctorTable").on("click", ".delete", function (event) {
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
                    var url = `${domainUrl}deleteDoctorNotification` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#doctorTable").DataTable().ajax.reload(null, false);
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
    $("#usersTable").on("click", ".delete", function (event) {
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
                    var url = `${domainUrl}deleteUserNotification` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#usersTable").DataTable().ajax.reload(null, false);
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
    $("#doctorTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var title = $(this).data("title");
        var description = $(this).data("description");
        var id = $(this).attr("rel");

        $("#editDoctorNotiId").val(id);
        $("#editDoctorNotiTitle").val(title);
        $("#editDoctorNotiDesc").val(description);

        $("#editDoctorNotiModal").modal("show");
    });
    $("#usersTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var title = $(this).data("title");
        var description = $(this).data("description");
        var id = $(this).attr("rel");

        $("#editUserNotiId").val(id);
        $("#editUserNotiTitle").val(title);
        $("#editUserNotiDesc").val(description);

        $("#editUserNotiModal").modal("show");
    });
    $("#addDoctorNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#addDoctorNotiForm")[0]);
            $.ajax({
                url: `${domainUrl}addDoctorNotification`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#addDoctorNotiModal").modal("hide");
                    $("#addDoctorNotiForm").trigger("reset");
                    $("#doctorTable").DataTable().ajax.reload(null, false);
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
    $("#addUserNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#addUserNotiForm")[0]);
            $.ajax({
                url: `${domainUrl}addUserNotification`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#addUserNotiModal").modal("hide");
                    $("#addUserNotiForm").trigger("reset");
                    $("#usersTable").DataTable().ajax.reload(null, false);
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
    $("#editUserNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#editUserNotiForm")[0]);
            $.ajax({
                url: `${domainUrl}editUserNotification`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#editUserNotiModal").modal("hide");
                    $("#editUserNotiForm").trigger("reset");
                    $("#usersTable").DataTable().ajax.reload(null, false);
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
    $("#editDoctorNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#editDoctorNotiForm")[0]);
            $.ajax({
                url: `${domainUrl}editDoctorNotification`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#editDoctorNotiModal").modal("hide");
                    $("#editDoctorNotiForm").trigger("reset");
                    $("#doctorTable").DataTable().ajax.reload(null, false);
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
});
