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

    $("#modelModal").on("hidden.bs.modal", function () {
        $("body").removeClass("modal-open");
        $(".modal-backdrop").remove();
        $("#modelModal").modal("hide");
    });

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
        var talant = $(img).data("talant");
        var languages = $(img).data("language");
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
        $("#modelTalant").text(talant);
        $("#modelLanguage").text(languages);

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

    $(".custom-select-multiple .option").click(function () {
        $(this).toggleClass("selected");
        updateInputs();
    });

    function updateInputs() {
        var selectedHairColors = $("#hair_color_options .option.selected")
            .map(function () {
                return $(this).data("value");
            })
            .get();
        var selectedEyeColors = $("#eye_color_options .option.selected")
            .map(function () {
                return $(this).data("value");
            })
            .get();
        var selectedTalants = $("#talant_options .option.selected")
            .map(function () {
                return $(this).data("value");
            })
            .get();
        var selectedLanguages = $("#language_options .option.selected")
            .map(function () {
                return $(this).data("value");
            })
            .get();

        $("#hair_color_input").val(selectedHairColors.join(","));
        $("#eye_color_input").val(selectedEyeColors.join(","));
        $("#talant_input").val(selectedTalants.join(","));
        $("#language_input").val(selectedLanguages.join(","));
    }

    // Retrieve filter section visibility state from sessionStorage
    var filterSectionVisible = sessionStorage.getItem("filterSectionVisible");
    if (filterSectionVisible === "true") {
        $("#filterSection").show();
        $("#toggleFilters").text("Hide Filters");
    } else {
        $("#filterSection").hide();
        $("#toggleFilters").text("Show Filters");
    }

    // Toggle filter section visibility and store state in sessionStorage
    $("#toggleFilters").click(function () {
        $("#filterSection").slideToggle(300, function () {
            if ($("#filterSection").is(":visible")) {
                $("#toggleFilters").text("Hide Filters");
                sessionStorage.setItem("filterSectionVisible", "true");
            } else {
                $("#toggleFilters").text("Show Filters");
                sessionStorage.setItem("filterSectionVisible", "false");
            }
        });
    });

    // Initialize the color inputs

    document.querySelector("img").onerror = function () {
        this.onerror = null; // Prevent infinite loop in case the default image is also missing
        this.src = "/uploads/default-image.jpg";
    };

    updateColorInputs();
});
