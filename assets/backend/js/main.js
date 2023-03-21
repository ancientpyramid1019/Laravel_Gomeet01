// iDevs Admin
(function ($) {

    'use strict';

    // Lucide Icons Activation
    lucide.createIcons();

    // Side Nav Collapse
    $(".sidebar-toggle").on('click', function () {
        $(".layout").toggleClass("nav-folded");
    });

    // Side Nav Hover
    $(".side-nav").on('mouseenter mouseleave', function () {
        $(".nav-folded .side-nav").toggleClass("side-nav-hover");
    });

    // Side Nav dropdowns
    $('.side-nav-dropdown > .dropdown-link').on('click', function () {
        $(".dropdown-items").slideUp(400);
        if (
            $(this)
                .parent()
                .hasClass("show")
        ) {
            $(".side-nav-dropdown").removeClass("show");
            $(this)
                .parent()
                .removeClass("show");
        } else {
            $(".side-nav-dropdown").removeClass("show");
            $(this)
                .next(".dropdown-items")
                .slideDown(400);
            $(this)
                .parent()
                .addClass("show");
        }
    });


    // Counter For Dashboard Card
    $('.count').counterUp({
        delay: 10,
        time: 2000
    });



// Image Preview
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


// Custom Toaster
    $('.toast__close').on('click',function (e) {
        e.preventDefault();
        var parent = $(this).parent('.site-toaster');
        parent.fadeOut("slow", function () {
            $(this).remove();
        });
    });


// Bootstrap Toast
    var toastTrigger = document.getElementById('liveToastBtn')
    var toastLiveExample = document.getElementById('liveToast')
    if (toastTrigger) {
        toastTrigger.addEventListener('click', function () {
            var toast = new bootstrap.Toast(toastLiveExample)

            toast.show()
        })
    }

// Simple Notify Js
    const btnn = document.querySelector('#btnn')
    if (btnn) {
        btnn.addEventListener('click', () => {
            new Notify({
                status: 'success',
                title: 'Success!',
                text: 'You made the changes.',
                effect: 'slide',
                speed: 300,
                customClass: '',
                customIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"></polyline></svg>',
                showIcon: true,
                showCloseButton: true,
                autoclose: false,
                autotimeout: 3000,
                gap: 20,
                distance: 20,
                type: 1,
                position: 'right top',
                customWrapper: '',
            })
        });
    }

// ToolTip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    //Text Editor
    $(document).ready(function () {
        $('.summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture',]],
                ['view', ['help']],
            ],
            styleTags: [
                'p','h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
            placeholder: 'Write Your Message',
            tabsize: 2,
            height: 220,
        });
        $('.note-editable').css('font-weight', '400');

    });


})(jQuery);
