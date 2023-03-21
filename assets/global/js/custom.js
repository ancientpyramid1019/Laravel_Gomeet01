function validateNumber(e) {
    "use strict";
    const pattern = /^[0-9]$/;
    return pattern.test(e.key)
}

function validateDouble($value) {
    "use strict";
    return $value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
}

function isWhatPercentOf(numA, numB) {
    "use strict";
    return (numA / numB) * 100;
}

function calPercentage(num, percentage) {
    "use strict";
    const result = num * (percentage / 100);
    return parseFloat(result.toFixed(2));
}

function imagePreview() {
    "use strict";
    $('input[type="file"]').each(function () {
        // Refs
        var $file = $(this),
            $label = $file.next('label'),
            $labelText = $label.find('span'),
            labelDefault = $labelText.text();

        // When a new file is selected
        $file.on('change', function (event) {
            var fileName = $file.val().split('\\').pop(),
                tmppath = URL.createObjectURL(event.target.files[0]);
            //Check successfully selection
            if (fileName) {
                $label
                    .addClass('file-ok')
                    .css('background-image', 'url(' + tmppath + ')');
                $labelText.text(fileName);
            } else {
                $label.removeClass('file-ok');
                $labelText.text(labelDefault);
            }
        });
    });
}

function imagePreviewAdd(title) {
    "use strict";
    var base_url = window.location.origin;

    var previewImage = $("#image-old");
    previewImage.css({
        'background-image': 'url(' + base_url + '/assets/' + title + ')'
    });
    previewImage.addClass("file-ok");
}


