$.fn.autocomplete2 = function (options) {
    // Defaults
    // array with objects
    // {
    //   text:'text',
    //   id: 'id',
    //   img: 'img',
    // }
    var defaults = {
        id: '',
        data: []
    };

    options = $.extend(defaults, options);

    return this.each(function () {
        var $input = $(this);
        var data = options.data,
            $inputDiv = $input.closest('.input-field'); // Div to append on

        // Check if data isn't empty
        if(!$.isEmptyObject(data)) {
            // Create autocomplete element
            var $autocomplete = $('<ul class="autocomplete-content dropdown-content"></ul>');

            // Append autocomplete element
            if($inputDiv.length) {
                $inputDiv.append($autocomplete); // Set ul in body
            }
            else {
                $input.after($autocomplete);
            }

            var highlight = function (string, $el) {
                var img = $el.find('img');
                var matchStart = $el.text().toLowerCase().indexOf("" + string.toLowerCase() + ""),
                    matchEnd = matchStart + string.length - 1,
                    beforeMatch = $el.text().slice(0, matchStart),
                    matchText = $el.text().slice(matchStart, matchEnd + 1),
                    afterMatch = $el.text().slice(matchEnd + 1);
                $el.html("<span>" + beforeMatch + "<span class='highlight'>" + matchText + "</span>" + afterMatch + "</span>");
                if(img.length) {
                    $el.prepend(img);
                }
            };

            $input.on('click', function (e) {
                for (var key of data) {
                    var autocompleteOption = $('<li data-id=' + key.id + '></li>');
                    if (!!key.img) {
                        autocompleteOption.append('<img src="' + key.img + '" class="right circle"><span>' + key.text + '</span>');
                    }
                    else {
                        autocompleteOption.append('<span>' + key.text + '</span>');
                    }
                    $autocomplete.append(autocompleteOption);
                }
            });

            // Perform search
            $input.on('keyup', function (e) {
                // Capture Enter
                if(e.which === 13) {
                    $autocomplete.find('li').first().click();
                    return;
                }

                var val = $input.val().toLowerCase();
                $autocomplete.empty();

                // Check if the input isn't empty
                if(val !== '') {
                    for(var key of data) {
                        if(key.hasOwnProperty('text') &&
                            key.text.toLowerCase().indexOf(val) !== -1 &&
                            key.text.toLowerCase() !== val) {
                            var autocompleteOption = $('<li data-id=' + key.id + '></li>');
                            if(!!key.img) {
                                autocompleteOption.append('<img src="' + key.img + '" class="right circle"><span>' + key.text + '</span>');
                            }
                            else {
                                autocompleteOption.append('<span>' + key.text + '</span>');
                            }
                            $autocomplete.append(autocompleteOption);

                            highlight(val, autocompleteOption);
                        }
                    }
                }
            });

            // Set input value
            $autocomplete.on('click', 'li', function () {
                $input.val($(this).text().trim());
                $input.data('id', $(this).data('id'));
                $input.trigger('change');
                // console.log($input.data('id'));
                $autocomplete.empty();
            });
        }
    });
};

objectDiff = function (a, b, c) {
    c = {};
    $.each([a, b], function(index, obj) {
        for (prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                if (typeof obj[prop] === "object" && obj[prop] !== null) {
                    c[prop] = objectDiff(a[prop], b[prop], c);
                }
                else {
                    if(a === undefined) a = {};
                    if(b === undefined) b = {};
                    if (a[prop] !== b[prop]) {
                        c[prop] = [a[prop], b[prop]];
                    }
                }
            }
        }
    });
    return c;
};

$(document).on('click', '.popup_selector', function (event) {
    event.preventDefault();
    let updateID;
    updateID = $(this).attr('data-inputid'); // Btn id clicked
    let elfinderUrl;
    elfinderUrl = $(this).attr('href');

    // trigger the reveal modal with elfinder inside
    let triggerUrl;
    triggerUrl = elfinderUrl + '/' + updateID;
    $.colorbox({
        href: triggerUrl,
        fastIframe: true,
        iframe: true,
        width: '70%',
        height: '435px'
    });

});

// function to update the file selected by elfinder
function processSelectedFile(filePath, requestingField) {          
    $('#' + requestingField).val(filePath).change();
    //$('#' + requestingField).triggerHandler('input');
    //$('#' + requestingField).triggerHandler('change');
}
