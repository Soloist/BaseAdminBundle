$ ->
    #
    #
    # Replaces checkboxes by bootstrap buttons
    $('.bootstrap-checkboxes .controls').each ->
        $baseContainer = $ this;
        $container     = $ '<div class="btn-group controls" data-toggle="buttons-checkbox"></div>'
        # For each checkboxes
        $(this).find('input[type=checkbox]').each ->
            $this = $ this
            # Let's create the button
            $button = $ '<button class="btn"></button>'
            $button.text $baseContainer.find('label[for="' + $this.attr('id') + '"]').text()
            $button.appendTo $container
            # Sets its state
            if $this.attr 'checked'
                $button.bbutton 'toggle'
            # Add an handler
            $button.click ->
                $this.attr 'checked', !$this.attr 'checked'
                $button.bbutton 'toggle'
                return false
        # Hide the base checkboxes, and add our beautifull buttons to the dom
        $baseContainer.after $container
        $baseContainer.hide()
