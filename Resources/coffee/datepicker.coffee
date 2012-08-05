# Bootstrap datepicker
(($) ->
    # Datepicker creation function
    createDatePicker = (data, id, lang) ->
        datepickerId = 'soloist-datepicker-' + id
        # Dom elements
        $monthSelect  = $ '#' + data.monthId
        $daySelect    = $ '#' + data.dayId
        $yearSelect   = $ '#' + data.yearId
        $template     = $ """
          <input type="text" id="#{datepickerId}" data-date-format="#{data.format}"
                 placeholder="#{data.placeholder}" />'
        """
        year    = $yearSelect.val()
        day     = $daySelect.val()
        month   = $monthSelect.val()
        if year != '' or day != '' or month != ''
            $template.val data.format.replace /(mm|dd|yyyy)/gi, (param) ->
                value = switch param
                    when 'yyyy', 'yy' then $yearSelect.val()
                    when 'dd' then $daySelect.val()
                    when 'mm' then $monthSelect.val()
                if value.length > 1 then value else "0" + value

        weekStart = if lang == 'fr' then 1 else 0;

        # Datepicker creation
        $daySelect.parent().find(':first-child').before $template
        $('#' + datepickerId).datepicker($.extend { language: lang, weekStart: weekStart }, window.soloist_form_date[id]).on 'changeDate', (ev) ->
           $monthSelect.val ev.date.getMonth() + 1
           $daySelect.val   ev.date.getDate()
           $yearSelect.val  ev.date.getFullYear()

        # Hide base date fields
        $daySelect.hide()
        $monthSelect.hide()
        $yearSelect.hide()

    $ ->
        # If some datepickers were setted
        if window.soloist_form_date?
            lang = if typeof window.soloist.locale is 'string' then window.soloist.locale.split('_')[0] else 'en'

            # Loop over datepickers
            createDatePicker data, id, lang for id, data of window.soloist_form_date

)(jQuery)
