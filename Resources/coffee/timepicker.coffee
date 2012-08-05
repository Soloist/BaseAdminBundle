# Bootstrap timepicker
(($) ->


    # Datepicker creation function
    createTimePicker = (data, id, options) ->
        timepickerId = 'soloist-timepicker-' + id
        # Dom elements
        $input = $ '#' + data.id

        # Datepicker creation
        $input.timepicker $.extend {}, options, { defaultTime: $input.val() }

    $ ->
        # If some datepickers were setted
        if window.soloist_form_time?
            # Defining default options
            options =
                showMeridian: false
                defaultTime:  ''
            # Loop over datepickers
            createTimePicker data, id, options for id, data of window.soloist_form_time
)(jQuery)
