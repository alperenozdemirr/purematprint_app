@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('content');
    if (!textarea) return;

    CKEDITOR.replace('content', {
      height: 420,
      removePlugins: 'elementspath',
      resize_enabled: true,
    });

    const form = textarea.closest('form');
    if (form) {
      form.addEventListener('submit', function () {
        if (typeof CKEDITOR !== 'undefined') {
          for (const instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
          }
        }
      });
    }
  });
</script>
@endpush
