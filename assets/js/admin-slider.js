jQuery(function($){
    function renumberRows(){
        $('#tc4-slides-rows .tc4-slide-row').each(function(i){
            $(this).find('.tc4-slide-number').text(i + 1);
            $(this).find('input, textarea, select').each(function(){
                var name = $(this).attr('name');
                if(name){
                    $(this).attr('name', name.replace(/tc4_slides\[\d+\]/, 'tc4_slides[' + i + ']'));
                }
            });
        });
    }

    $('#tc4-add-slide').on('click', function(){
        var index = $('#tc4-slides-rows .tc4-slide-row').length;
        var html = $('#tc4-slide-template').html().replace(/__INDEX__/g, index);
        $('#tc4-slides-rows').append(html);
        renumberRows();
    });

    $(document).on('click', '.tc4-remove-slide', function(){
        $(this).closest('tr').remove();
        renumberRows();
    });

    $(document).on('click', '.tc4-select-image', function(e){
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
            row.find('.tc4-slide-image-id').val(attachment.id);
            var src = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            row.find('.tc4-slide-preview').html('<img src="' + src + '" alt="" style="max-width:120px;height:auto;">');
        });
        frame.open();
    });

    renumberRows();
    $('#tc4-select-video').on('click', function(e){
        e.preventDefault();
        var frame = wp.media({
            title: 'Video kiezen',
            button: { text: 'Gebruik video' },
            library: { type: 'video' },
            multiple: false
        });

        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $('#tc4_slider_video_id').val(attachment.id);
            $('#tc4-slider-video-preview').html(
                '<video src="' + attachment.url + '" style="max-width:320px;height:auto;" controls muted></video>' +
                '<p><code>' + (attachment.filename || attachment.url.split('/').pop()) + '</code></p>'
            );
        });

        frame.open();
    });

    $('#tc4-remove-video').on('click', function(e){
        e.preventDefault();
        $('#tc4_slider_video_id').val('');
        $('#tc4-slider-video-preview').empty();
    });

});
