$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");

    var userId = $("#userId").val();
    console.log(userId);

    $("#walletRechargeLogsTable").dataTable({
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
            url: `${domainUrl}fetchUserWalletRechargeLogsList`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#tabWithdrawRequestsTable").dataTable({
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
            url: `${domainUrl}fetchUserWithdrawRequestsList`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#walletStatementTable").dataTable({
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
            url: `${domainUrl}fetchUserWalletStatementList`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#patientsTable").dataTable({
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
            url: `${domainUrl}fetchUserPatientsList`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#appointmentsTable").dataTable({
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchUserAppointmentsList`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#tabWithdrawRequestsTable").on("click", ".complete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        $("#completeId").val(id);

        $("#completeModal").modal("show");
    });
    $("#tabWithdrawRequestsTable").on("click", ".reject", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        $("#rejectId").val(id);

        $("#rejectModal").modal("show");
    });

    $("#completeForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#completeForm")[0]);
            $.ajax({
                url: `${domainUrl}completeUserWithdrawal`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#completeModal").modal("hide");
                    $("#completeForm").trigger("reset");
                    $("#tabWithdrawRequestsTable")
                        .DataTable()
                        .ajax.reload(null, false);
                    $("#walletStatementTable")
                        .DataTable()
                        .ajax.reload(null, false);
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
    $("#rejectForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#rejectForm")[0]);
            $.ajax({
                url: `${domainUrl}rejectUserWithdrawal`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#rejectModal").modal("hide");
                    $("#rejectForm").trigger("reset");
                    $("#tabWithdrawRequestsTable")
                        .DataTable()
                        .ajax.reload(null, false);
                    $("#walletStatementTable")
                        .DataTable()
                        .ajax.reload(null, false);
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

    $("#rechargeWallet").on("click", function (event) {
        event.preventDefault();
        $("#rechargeWalletModal").modal("show");
    });

    $("#rechargeWalletForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#rechargeWalletForm")[0]);
            $.ajax({
                url: `${domainUrl}rechargeWalletFromAdmin`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#rechargeWalletModal").modal("hide");
                    $("#rechargeWalletForm").trigger("reset");
                    window.location.reload();
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

    $("#blockUser").on("click", function (event) {
        event.preventDefault();
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                if (user_type == "1") {
                    var url = `${domainUrl}blockUserFromAdmin` + "/" + userId;
                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        window.location.reload();
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
    $("#unblockUser").on("click", function (event) {
        event.preventDefault();
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                if (user_type == "1") {
                    var url = `${domainUrl}unblockUserFromAdmin` + "/" + userId;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        window.location.reload();
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
