jQuery(function($){
  $(document).on('click', '.tc4-select-home-image', function(e){
    e.preventDefault();
    var wrap = $(this).closest('p, td, div');
    var idField = wrap.find('.tc4-image-id').first();
    var preview = wrap.find('.tc4-image-preview').first();
    var frame = wp.media({
      title: 'Afbeelding kiezen',
      button: { text: 'Gebruik afbeelding' },
      multiple: false
    });
    frame.on('select', function(){
      var attachment = frame.state().get('selection').first().toJSON();
      idField.val(attachment.id);
      var src = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
      preview.html('<img src="' + src + '" alt="" style="max-width:140px;height:auto;">');
    });
    frame.open();
  });

  $(document).on('click', '.tc4-remove-home-image', function(e){
    e.preventDefault();
    var wrap = $(this).closest('p, td, div');
    wrap.find('.tc4-image-id').first().val('');
    wrap.find('.tc4-image-preview').first().empty();
  });
});
