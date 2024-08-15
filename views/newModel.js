// Get the slider and value display elements
const slider = document.getElementById("customRange2");
const sliderValue = document.getElementById("sliderValue");

// Update the value display when the slider value changes
slider.addEventListener("input", function () {
    sliderValue.textContent = this.value;
});
// a
