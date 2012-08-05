(($) ->
    #
    #
    # Define Function.prototype.bind for old browsers
    if not Function::bind?
        Function::bind = (context, boundArgs...) ->
            (args...) =>
                Array::push.apply boundArgs, args
                @apply context, boundArgs

    window.soloist = {} if not window.soloist?

    #
    #
    # Function for replacing textareas by redactor.js
    replaceTextareas = window.soloist.replaceTextareas = ->
        $('textarea')
            .css({ height: "400px"})
            .redactor()

    $ ->

        #
        #
        # Find elements tagged with class ask-delete to confirm deletion
        $('.ask-delete').click ->
            confirm 'Etes vous sur de vouloir supprimer cet élément ?'

        #
        #
        # Replace textareas by div managed by redactor.js
        replaceTextareas()

)(jQuery)
