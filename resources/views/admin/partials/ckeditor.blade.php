{{-- CKEditor 5 (self-hosted npm build via jsDelivr, GPL — no Cloud license / EOL warnings) --}}
@php
  $ckeditorElementId = $ckeditorElementId ?? 'content';
  $ckeditorHeight = $ckeditorHeight ?? 320;
@endphp

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ckeditor5@48.4.0/dist/ckeditor5.css">
<style>
  .ck.ck-editor { width: 100%; }
  .ck.ck-editor__main > .ck-editor__editable {
    min-height: {{ (int) $ckeditorHeight }}px;
    background: #f7f4ef;
    color: #1a1a1a;
    font-size: 14px;
    line-height: 1.6;
  }
  .ck.ck-toolbar { border-color: rgba(26, 26, 26, 0.1) !important; }
  .ck.ck-editor__main > .ck-editor__editable:not(.ck-focused) {
    border-color: rgba(26, 26, 26, 0.1) !important;
  }
</style>
<script src="https://cdn.jsdelivr.net/npm/ckeditor5@48.4.0/dist/browser/ckeditor5.umd.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById(@json($ckeditorElementId));
    if (!element || typeof CKEDITOR === 'undefined') return;

    const {
      ClassicEditor,
      Essentials,
      Bold,
      Italic,
      Underline,
      Strikethrough,
      Heading,
      Paragraph,
      Link,
      List,
      Alignment,
      BlockQuote,
      Undo,
      GeneralHtmlSupport,
    } = CKEDITOR;

    ClassicEditor
      .create(element, {
        licenseKey: 'GPL',
        plugins: [
          Essentials,
          Bold,
          Italic,
          Underline,
          Strikethrough,
          Heading,
          Paragraph,
          Link,
          List,
          Alignment,
          BlockQuote,
          Undo,
          GeneralHtmlSupport,
        ],
        toolbar: {
          items: [
            'undo', 'redo', '|',
            'heading', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'alignment', '|',
            'bulletedList', 'numberedList', '|',
            'link', 'blockQuote',
          ],
          shouldNotGroupWhenFull: true,
        },
        heading: {
          options: [
            { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
            { model: 'heading2', view: 'h2', title: 'Başlık 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Başlık 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Başlık 4', class: 'ck-heading_heading4' },
          ],
        },
        htmlSupport: {
          allow: [
            {
              name: /^(p|br|strong|b|em|i|u|s|ul|ol|li|a|h2|h3|h4|blockquote|span)$/,
              attributes: true,
              classes: true,
              styles: true,
            },
          ],
        },
        link: {
          addTargetToExternalLinks: true,
          defaultProtocol: 'https://',
        },
      })
      .then(function (editor) {
        const form = element.closest('form');
        if (!form) return;

        form.addEventListener('submit', function () {
          editor.updateSourceElement();
        });
      })
      .catch(function (error) {
        console.error('CKEditor başlatılamadı:', error);
      });
  });
</script>
@endpush
