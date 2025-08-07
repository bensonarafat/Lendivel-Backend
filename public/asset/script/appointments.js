$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".appointmentsSideA").addClass("activeLi");

    $("#allAppointmentTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchAllAppointmentsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#pendingAppointmentTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchPendingAppointmentsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#acceptedAppointmentTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchAcceptedAppointmentsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#completedAppointmentTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchCompletedAppointmentsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#cancelledAppointmentTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchCancelledAppointmentsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#declinedAppointmentTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchDeclinedAppointmentsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
});
