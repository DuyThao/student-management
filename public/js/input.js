(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    };
}(jQuery));



$("#score").inputFilter(function (value) {
    return /^-?\d*[.,]?\d*$/.test(value) && (value === "" || parseInt(value) <= 10);
});
$("#update_score").inputFilter(function (value) {
    return /^-?\d*[.,]?\d*$/.test(value) && (value === "" || parseInt(value) <= 10);
});






