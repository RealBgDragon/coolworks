$(document).ready(function () {
    // Initialize the age range slider
    $("#age_slider").slider({
        range: true,
        min: 10, // Minimum age is 10
        max: 100, // Maximum age is 100
        values: [10, 100], // Default values
        slide: function (event, ui) {
            $("#age_range").val(ui.values[0] + " - " + ui.values[1]);
        },
        change: function (event, ui) {
            $("#filterForm").submit(); // Submit the form when the slider changes
        },
    });

    // Set initial values
    $("#age_range").val(
        $("#age_slider").slider("values", 0) +
            " - " +
            $("#age_slider").slider("values", 1)
    );

    // Update the existing showModelDetails function
    window.showModelDetails = function (img) {
        var modelId = $(img).data("model-id");
        var modelName = $(img).data("model-name");
        var modelAge = $(img).data("model-age");
        var modelHeight = $(img).data("model-height");
        var modelWeight = $(img).data("model-weight");
        var modelBirthday = $(img).data("model-birthday");
        var modelPhone = $(img).data("model-phone");
        var eyeColor = $(img).data("eye-color");
        var hairColor = $(img).data("hair-color");
        var imageSrc = $(img).attr("src");

        $("#modalModelImage").attr("src", imageSrc);
        $("#modelName").text(modelName);
        $("#modelAge").text(modelAge);
        $("#modelHeight").text(modelHeight);
        $("#modelWeight").text(modelWeight);
        $("#modelBirthday").text(modelBirthday);
        $("#modelPhone").text(modelPhone);
        $("#modelEyeColor").text(eyeColor);
        $("#modelHairColor").text(hairColor);

        $("#modelModal").modal("show");
    };

    // Handle form submission
    $("#filterForm").on("submit", function (e) {
        e.preventDefault();

        var ageRange = $("#age_slider").slider("values");
        var minAge = ageRange[0];
        var maxAge = ageRange[1];

        console.log("Filtering with age range:", minAge, "-", maxAge);
    });
});
