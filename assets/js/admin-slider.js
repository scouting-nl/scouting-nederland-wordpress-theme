jQuery(function($){
    function renumberRows(){
        $('#scouting-nederland-slides-rows .scouting-nederland-slide-row').each(function(i){
            $(this).find('.scouting-nederland-slide-number').text(i + 1);
            $(this).find('input, textarea, select').each(function(){
                var name = $(this).attr('name');
                if(name){
                    $(this).attr('name', name.replace(/scouting_nederland_slides\[\d+\]/, 'scouting_nederland_slides[' + i + ']'));
                }
            });
        });
    }

    $('#scouting-nederland-add-slide').on('click', function(){
        var index = $('#scouting-nederland-slides-rows .scouting-nederland-slide-row').length;
        var html = $('#scouting-nederland-slide-template').html().replace(/__INDEX__/g, index);
        $('#scouting-nederland-slides-rows').append(html);
        renumberRows();
    });

    $(document).on('click', '.scouting-nederland-remove-slide', function(){
        $(this).closest('tr').remove();
        renumberRows();
    });

    $(document).on('click', '.scouting-nederland-select-image', function(e){
        e.preventDefault();
        var button = $(this);
        var row = button.closest('tr');
        var frame = wp.media({
            title: 'Afbeelding kiezen',
            button: { text: 'Gebruik afbeelding' },
            multiple: false
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            row.find('.scouting-nederland-slide-image-id').val(attachment.id);
            var src = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            row.find('.scouting-nederland-slide-preview').html('<img src="' + src + '" alt="" style="max-width:120px;height:auto;">');
        });
        frame.open();
    });

    renumberRows();
    $('#scouting-nederland-select-video').on('click', function(e){
        e.preventDefault();
        var frame = wp.media({
            title: 'Video kiezen',
            button: { text: 'Gebruik video' },
            library: { type: 'video' },
            multiple: false
        });

        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $('#scouting_nederland_slider_video_id').val(attachment.id);
            $('#scouting-nederland-slider-video-preview').html(
                '<video src="' + attachment.url + '" style="max-width:320px;height:auto;" controls muted></video>' +
                '<p><code>' + (attachment.filename || attachment.url.split('/').pop()) + '</code></p>'
            );
        });

        frame.open();
    });

    $('#scouting-nederland-remove-video').on('click', function(e){
        e.preventDefault();
        $('#scouting_nederland_slider_video_id').val('');
        $('#scouting-nederland-slider-video-preview').empty();
    });

});
