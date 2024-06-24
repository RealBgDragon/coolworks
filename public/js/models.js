function showModelDetails(img) {
    var modelId = $(img).data("model-id");
    var modelName = $(img).data("model-name");
    var modelAge = $(img).data("model-age");
    var imageSrc = $(img).attr("src");

    $("#modalModelImage").attr("src", imageSrc);
    $("#modelName").text(modelName);
    $("#modelAge").text(modelAge);

    $("#modelModal").modal("show");
}
