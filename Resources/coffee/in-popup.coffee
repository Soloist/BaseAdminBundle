$ ->

    # If we're in media pop-up
    if window.opener
        # Hack links and forms
        $('a:not([href="#"], [href$="?nolayout"])').click ->
            $(this).attr 'href', $(this).attr('href') + '?nolayout'
            return true
        $('form:not([action="#"], [action$="?nolayout"])').submit ->
            $(this).attr 'action', $(this).attr('action') + '?nolayout'
            return true
        # Transmit result to parent
        $('.media-selector').click ->
            # Fetching fields
            $fieldsContainer = $(window.opener.document.getElementById 'soloist-media-subject')
            # Setting attributes
            for index, element of window.soloist_media_bundle[$(this).attr 'data-id']
                $fieldsContainer.find('[name*="' + index + '"]').val element
            # Paste the thumbnail
            $fieldsContainer.find('.thumbnail-subject').html $(this).find('.thumbnail-subject').html()
            # Reseting & cleaning
            $fieldsContainer.attr 'id', ''
            window.close()
