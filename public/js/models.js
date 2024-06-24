function showModelDetails(img) {
    var modelId = $(img).data("model-id");
    var modelName = $(img).data("model-name");
    var modelAge = $(img).data("model-age");
    var modelHeight = $(img).data("model-height");
    var modelWeight = $(img).data("model-weight");
    var modelBirthday = $(img).data("model-birthday");
    var modelPhone = $(img).data("model-phone");
    var imageSrc = $(img).attr("src");

    $("#modalModelImage").attr("src", imageSrc);
    $("#modelName").text(modelName);
    $("#modelAge").text(modelAge);
    $("#modelHeight").text(modelHeight);
    $("#modelWeight").text(modelWeight);
    $("#modelBirthday").text(modelBirthday);
    $("#modelPhone").text(modelPhone);

    $("#modelModal").modal("show");
}
