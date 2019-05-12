tinymce.init({
    selector: 'textarea#contenu',
    themes: 'modern',
    height: 550,
    languag : "fr_FR",
    image_advtab: true,
    plugins: [ 'print preview fullpage searchreplace autolink directionality visualblocks visualchars ' +
    'fullscreen link template codesample table charmap hr pagebreak nonbreaking ' +
    'anchor toc insertdatetime advlist lists wordcount imagetools textpattern ' +
    'help',
    ],
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen  | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
    image_caption: true,

});


