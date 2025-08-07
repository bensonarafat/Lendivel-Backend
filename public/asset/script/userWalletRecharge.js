$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".userWalletRechargeSideA").addClass("activeLi");

    $("#walletRechargeTable").dataTable({
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
            url: `${domainUrl}fetchWalletRechargeList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
});
