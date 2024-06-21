function showModelDetails(img) {
    var modelId = img.getAttribute("data-model-id");

    // AJAX request to get model details
    $.ajax({
        url: "/get-model-details.php", // Create this PHP file to handle the request
        method: "GET",
        data: { model_id: modelId },
        success: function (response) {
            $("#modelModalBody").html(response);
        },
        error: function () {
            $("#modelModalBody").html("Error loading model details.");
        },
    });
}
