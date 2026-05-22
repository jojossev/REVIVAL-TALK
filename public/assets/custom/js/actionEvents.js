window.authorEvents = {
    'click .edit-data': function (e, value, row) {

         // Get the data-user-id from the clicked button
         var userId = $(e.currentTarget).data('user-id');
         var authorId = $(e.currentTarget).data('id');

        $('#telegram_link').val(row.telegram_link);
        $('#whatsapp_link').val(row.whatsapp_link);
        $('#linkedin_link').val(row.linkedin_link);
        $('#facebook_link').val(row.facebook_link);

        // Set the hidden input values
        $('#edit_id').val(authorId);
        $('#user_id').val(userId);

        // Set the form action URL with the correct ID
        //  var form = $('#update_form');
        //  var actionUrl = form.attr('action').replace(/\/\d+$/, '/' + authorId);
        //  form.attr('action', actionUrl);

        // Set the status radio button if needed
        if (row.status) {
            $('input[name="author_status"][value="' + row.status + '"]').prop('checked', true);
        }
    },
};


window.eNewsEvents = {
    'click .edit-data': function (e, value, row) {
        const normalizeDateForInput = (dateValue) => {
            if (!dateValue) return '';

            const dateStr = String(dateValue).trim();

            // Already in HTML date input format.
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                return dateStr;
            }

            // // Convert from DD-MM-YYYY to YYYY-MM-DD.
            // if (/^\d{2}-\d{2}-\d{4}$/.test(dateStr)) {
            //     const [dd, mm, yyyy] = dateStr.split('-');
            //     return `${yyyy}-${mm}-${dd}`;
            // }

            // // Handle datetime strings like "YYYY-MM-DD HH:mm:ss".
            // const isoPart = dateStr.split(' ')[0];
            // if (/^\d{4}-\d{2}-\d{2}$/.test(isoPart)) {
            //     return isoPart;
            // }

            return '';
        };

        $('#edit_id').val(row.id);
        $('#edit_language').val(row.language_id).trigger('change');
        $('#edit_title').val(row.title);
        $('#edit_slug').val(row.slug);
        // $('#edit_date').val(normalizeDateForInput(row.date));
        $('#edit_date').val(row.date);
        let des1 = tinyMCE.get('edit_des').setContent(row.description);
        $('#edit_des').val(des1);
        // $('#edit_des').val(row.description);
        $('#edit_meta_title').val(row.meta_title);
        getWordCount('edit_meta_title', 'edit_meta_title_count', '19.9px arial');
        $('#edit_meta_tags').val(row.meta_keyword);
        $('#edit_schema_markup').val(row.schema_markup);
        $('#edit_meta_description').val(row.meta_description);
        getWordCount('edit_meta_description', 'edit_meta_description_count', '12.9px arial');

        var editSwitch = document.querySelector('#edit_status_switch');
        var shouldBeChecked = (row.raw_status == 1);
        if (editSwitch.checked !== shouldBeChecked) {
            editSwitch.click();
        }
        $('#edit_status').val(shouldBeChecked ? 1 : 0);
    },
};

window.staffEvents = {
    'click .edit-data': function (e, value, row) {
        console.log(row);


        $('#edit_id').val(row.id);
        $('#edit_username').val(row.username);
        $('#edit_email').val(row.email);
        $('#edit_role').val(row.role_id);
        $('input[name="status"][value="' + row.status + '"]').prop('checked', true);
    },
}
