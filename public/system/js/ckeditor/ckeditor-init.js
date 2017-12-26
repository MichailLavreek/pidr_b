ClassicEditor
    .create( document.querySelector( '.custom-ckeditor-container textarea' ) )
    .then( editor => {
        console.log( editor );
    } )
    .catch( error => {
        console.error( error );
    } );