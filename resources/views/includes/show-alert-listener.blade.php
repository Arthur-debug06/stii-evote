<script>
    // Global Livewire alert listener: shows a browser alert when Livewire emits "show-alert"
    document.addEventListener('livewire:load', function () {
        if (typeof Livewire !== 'undefined' && Livewire.on) {
            Livewire.on('show-alert', function(event) {
                try {
                    var title = (event && event.title) ? event.title : 'Alert';
                    var message = (event && event.message) ? event.message : '';
                    alert(title + '\n\n' + message);
                } catch (err) {
                    // Fallback in case event payload is unexpected
                    alert('Alert\n\n' + (typeof event === 'string' ? event : JSON.stringify(event)));
                }
            });
        }
    });
</script>
