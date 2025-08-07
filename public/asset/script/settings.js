$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".settingsSideA").addClass("activeLi");

    if(user_type != "1"){
        $('#chatGPTToken').val('sk-proj-Og40v4dph9ZlrwMbAIDubhndjfvbsdvfjishbicnscllxcxsadffUA');
    }

    $(document).on("change", "#chatBotSwitch", function (event) {
        event.preventDefault();

        if ($(this).prop("checked") == true) {
            var value = 1;
        } else {
            value = 0;
        }

        var updateEventStatusUrl =
            `${domainUrl}onOffChatBot` + "/" + value;

        $.getJSON(updateEventStatusUrl).done(function (data) {
            if (data.status) {
                iziToast.success({
                    title: "Update Successful..",
                    message: "Settings Updated Successfully !",
                    position: "topRight",
                });
            } else {
                iziToast.error({
                    title: "Failed!",
                    message: "Something went wrong!",
                    position: "topRight",
                });
            }
        });
    });

    $("#remindersTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchAllRemindersList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#remindersTable").on("click", ".delete", function (event) {
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
                    var url = `${domainUrl}deleteRemindersItem` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#remindersTable")
                            .DataTable()
                            .ajax.reload(null, false);
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

    $("#addReminderForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#addReminderForm")[0]);
            $.ajax({
                url: `${domainUrl}addReminderItem`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#addReminderModal").modal("hide");
                    $("#addReminderForm").trigger("reset");
                    $("#remindersTable").DataTable().ajax.reload(null, false);
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

    $("#taxesTable").dataTable({
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
            url: `${domainUrl}fetchAllTaxList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#addTaxForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#addTaxForm")[0]);
            $.ajax({
                url: `${domainUrl}addTaxItem`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#addTaxModal").modal("hide");
                    $("#addTaxForm").trigger("reset");
                    $("#taxesTable").DataTable().ajax.reload(null, false);
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
    $("#editTaxForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#editTaxForm")[0]);
            $.ajax({
                url: `${domainUrl}editTaxItem`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#editTaxModal").modal("hide");
                    $("#editTaxForm").trigger("reset");
                    $("#taxesTable").DataTable().ajax.reload(null, false);
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

    $("#taxesTable").on("change", ".onoff", function (event) {
        event.preventDefault();
        if ($(this).prop("checked") == true) {
            var value = 1;
        } else {
            value = 0;
        }
        var itemId = $(this).attr("rel");

        var url = `${domainUrl}changeTaxStatus/${itemId}/${value}`;

        $.getJSON(url).done(function (data) {
            $("#taxesTable").DataTable().ajax.reload(null, false);

            iziToast.success({
                title: strings.success,
                message: strings.operationSuccessful,
                position: "topRight",
                timeOut: 3000,
            });
        });
    });

    $("#taxesTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var id = $(this).attr("rel");
        var title = $(this).data("taxtitle");
        var value = $(this).data("value");
        var type = $(this).data("type");

        $("#editTaxId").val(id);
        console.log(title);
        $("#edit_tax_title").val(title);
        $("#edit_tax_value").val(value);

        $("#edit_tax_type").empty();
        $("#edit_tax_type").append(
            `<option ${type == 0 ? "selected" : ""} value="0">Percent</option>
            <option ${type == 1 ? "selected" : ""} value="1">Fixed</option>
            `
        );

        $("#editTaxModal").modal("show");
    });
    $("#taxesTable").on("click", ".delete", function (event) {
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
                    var url = `${domainUrl}deleteTaxItem` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#taxesTable").DataTable().ajax.reload(null, false);
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
    $("#globalSettingsForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        if (user_type == "1") {
            var formdata = new FormData($("#globalSettingsForm")[0]);
            $.ajax({
                url: `${domainUrl}updateGlobalSettings`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    location.reload();
                    // iziToast.success({
                    //     title: strings.success,
                    //     message: strings.operationSuccessful,
                    //     position: "topRight",
                    // });
                },
                error: (error) => {
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
    $("#passwordForm").on("submit", function (event) {
        event.preventDefault();
        if (user_type == "1") {
            var formdata = new FormData($("#passwordForm")[0]);
            $.ajax({
                url: domainUrl + "changePassword",
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if (response.status) {
                        iziToast.success({
                            title: strings.success,
                            message: strings.operationSuccessful,
                            position: "topRight",
                        });
                    } else {
                        iziToast.error({
                            title: strings.error,
                            message: response.message,
                            position: "topRight",
                        });
                    }
                    $("#passwordForm").trigger("reset");
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
    $("#paymentGatewayForm").on("submit", function (event) {
        event.preventDefault();
        if (user_type == "1") {
            var formdata = new FormData($("#paymentGatewayForm")[0]);
            $.ajax({
                url: domainUrl + "updatePaymentSettings",
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if (response.status) {
                        iziToast.success({
                            title: strings.success,
                            message: strings.operationSuccessful,
                            position: "topRight",
                        });
                    } else {
                        iziToast.error({
                            title: strings.error,
                            message: response.message,
                            position: "topRight",
                        });
                    }
                },
                error: (error) => {
                    console.log(JSON.stringify(error));
                },
            });
        } else {
            iziToast.error({
                title: strings.error,
                message: strings.youAreTester,
                position: "topRight",
            });
        }
    });
});
