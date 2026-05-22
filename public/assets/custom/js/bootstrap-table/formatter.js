// news table
function isDraftNewsFormatter(value, row, index) {
    if (value == '1') {
        return '<span class="badge badge-warning">' + trans('yes') + '</span>';
    } else {
        return '<span class="badge badge-success">' + trans('no') + '</span>';
    }
}

// author table
function statusFormatter(value, row, index) {
    // Ensure translations object exists
    // var trans = typeof translations !== 'undefined' ? translations : {};

    if (value == 'pending') {
        // return '<span class="badge badge-warning">' + trans.pending || 'Pending') + '</span>';
        return '<span class="badge badge-warning">' + trans('pending') + '</span>';
    } else if (value == 'approved') {
        return '<span class="badge badge-success">' + trans('approved') + '</span>';
    } else if (value == 'rejected') {
        return '<span class="badge badge-danger">' + trans('rejected') + '</span>';
    }
    return '';
}


// table column text show more and less formatter
function textFormatter(value, row, index) {
    if (value?.length > 100) {
        return '<div class="short-description">' + value.substring(0, 50) +
            '... <a href="#" class="view-more" data-index="' + index + '">' + trans('see_more') + '</a></div>' +
            '<div class="full-description" style="display:none;">' + value +
            ' <a href="#" class="view-more" data-index="' + index + '">' + trans('see_less') + '</a></div>';
    } else {
        return value;
    }
}

// news table
// function smallTextFormatter (value, row, index){
//     if (!value) return '';

//     // Remove heading tags and other large tags
//     let sanitized = value.replace(/<\/?h[1-6][^>]*>/gi, '');

//     // Optionally, remove all HTML comments
//     sanitized = sanitized.replace(/<!--.*?-->/gs, '');

//     return `<small style="font-size: 1.0rem;">${sanitized}</small>`;
// }

// breaking news
function descriptionFormatter(value, row, index) {
    if (!value) return '';
    let tempDiv = document.createElement('div');
    tempDiv.innerHTML = value;
    let textContent = tempDiv.textContent || tempDiv.innerText || '';

    if (textContent.length > 20) {
        return '<div class="description-container">' +
            '<div class="short-desc">' + textContent.substring(0, 20) + '...</div>' +
            '<div class="full-desc" style="display:none">' + value + '</div>' +
            '<a href="javascript:void(0)" class="toggle-desc" data-show="more">' + trans('see_more') + '</a>' +
            '</div>';
    }
    return value;
}

// news table
function dateFormate(value, row) {
    if (value && value !== '0000-00-00') {
        var date = new Date(value);
        var yy = date.getFullYear();
        var mm = date.getMonth() + 1; // getMonth() is zero-based
        var dd = date.getDate();
        return dd.toString().padStart(2, '0') + '-' + mm.toString().padStart(2, '0') + '-' + yy;
    }
    return '00-00-0000';
}
function MM_DD_YYYY_dateFormate(value, row) {
    if (value && value !== '0000-00-00') {
        var date = new Date(value);
        var mm = date.getMonth() + 1; // getMonth() is zero-based
        var dd = date.getDate();
        var yy = date.getFullYear();
        return mm.toString().padStart(2, '0') + '-' + dd.toString().padStart(2, '0') + '-' + yy;
    }
    return '00-00-0000';
}


function eNewsStatusFormatter(value, row, index) {
    if (value == 0) {
        return '<span class="badge badge-danger">' + trans('deactive') + '</span>';
    } else if (value == 1) {
        return '<span class="badge badge-success">' + trans('active') + '</span>';
    }
    return '-';
}
function isShortNewsFormatter(value, row, index) {
    if (value == '1') {
        return '<span class="badge badge-warning">' + trans('yes') + '</span>';
    } else {
        return '<span class="badge badge-success">' + trans('no') + '</span>';
    }
}

function newsCountFormatter(value, row, index) {
    return '<span class="btn btn-icon btn-sm btn-success move" alt="Move" >' + value + '</span>';
}
function rowOrderFormatter(value, row, index) {
    return '<span class="btn btn-icon btn-sm btn-warning move" alt="Move" >' + value + '</span>';
}
function generalImageFormatter(value, row, index) {
    return '<a href="' + value + '" data-toggle="lightbox" data-title="Image"><img  class = "images_border" src="' + value + '" height="50" width="50"></a>';
}
function attachmentFormatter(value, row, index) {
    // icon size 16px
    return value ? '<a href="' + value + '" target="_blank"><i class="fas fa-file-pdf" style="font-size: 36px;"></i></a>' : '-';
}
function staffStatusFormatter(value, row, index) {
    if (value == 1) {
        return '<span class="badge badge-success">' + trans('active') + '</span>';
    } else if (value == 0) {
        return '<span class="badge badge-danger">' + trans('deactive') + '</span>';
    }
    return '-';
}
