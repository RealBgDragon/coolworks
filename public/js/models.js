$(document).ready(function () {
    // Function to get query parameters from URL
    function getQueryParams() {
        var params = {};
        var queryString = window.location.search.slice(1);
        var queryArray = queryString.split("&");
        for (var i = 0; i < queryArray.length; i++) {
            var pair = queryArray[i].split("=");
            params[pair[0]] = decodeURIComponent(pair[1]);
        }
        return params;
    }

    // Extract the age range from the query parameters
    var params = getQueryParams();
    var ageRange = params.age_range
        ? params.age_range.split("+-+").map(Number)
        : [10, 100];
    var ageMin = ageRange[0];
    var ageMax = ageRange[1];

    // Initialize the age range slider
    $("#age_slider").slider({
        range: true,
        min: 10, // Minimum age is 10
        max: 100, // Maximum age is 100
        values: [ageMin, ageMax], // Default values
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
        /* var imageSrc = $(img).attr("src"); */

        var imageSrcString = $(img).data("model-srcs");
        var imageSrcs = imageSrcString.split(",").map((src) => src.trim());

        $("#carouselInner").empty();

        // Populate the carousel with images
        imageSrcs.forEach(function (imageSrc, index) {
            var activeClass = index === 0 ? "active" : "";
            var carouselItem = `<div class="carousel-item ${activeClass}">
                            <img src="${imageSrc}" class="d-block w-100" alt="Model Image">
                        </div>`;
            $("#carouselInner").append(carouselItem);
        });

        /* $("#modalModelImage").attr("src", imageSrc); */
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
    $(".carousel-control-prev").click(function () {
        $("#modelCarousel").carousel("prev");
    });

    $(".carousel-control-next").click(function () {
        $("#modelCarousel").carousel("next");
    });
});
