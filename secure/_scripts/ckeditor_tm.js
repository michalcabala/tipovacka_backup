ClassicEditor
    .create( document.querySelector( '.editor' ), {
        toolbar:
            {
            items: [
                'heading', //nadpisy odstavce
                '|',
                'bold', //tucne
                'italic', //kurziva
                'underline', //podtrzeni
                'strikethrough',
                'code',
                'subscript',
                'superscript',
                'link', //
                '|',
                'bulletedList',
                'numberedList',
                'todoList',
                '|',
                'outdent',
                'indent',
                'alignment',
                '|',
                'horizontalLine',
                'blockQuote',
                'insertTable',
                'mediaEmbed',
                'imageInsert',
                'ImageResize',
                'undo',
                'redo',
                'CKFinder',
                '|',
                'fontBackgroundColor',
                'fontColor',
                'fontSize',
                'sourceEditing',],
                shouldNotGroupWhenFull: true
            },
        ckfinder: {
            uploadUrl: '/_scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
        },
        language: 'cs',
        image:
            {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:full',
                'imageStyle:inline',
                'imageStyle:side',
                'imageStyle:block',
                'toggleImageCaption',
                'imageStyle:wrapText',
                'imageStyle:breakText',
                'ImageResize',
                'linkImage']
            },
        table:
            {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells',
                'tableCellProperties',
                'tableProperties']
            },

        } );