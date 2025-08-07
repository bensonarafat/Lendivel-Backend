$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".doctorsSideA").addClass("activeLi");

    $("#allDoctorsTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
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
            url: `${domainUrl}fetchAllDoctorsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#approvedDoctorsTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
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
            url: `${domainUrl}fetchApprovedDoctorsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#pendingDoctorsTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
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
            url: `${domainUrl}fetchPendingDoctorsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#bannedDoctorsTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
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
            url: `${domainUrl}fetchBannedDoctorsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
});
