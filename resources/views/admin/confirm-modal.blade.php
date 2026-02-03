<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white" id="confirmModalHeader">
                <h5 class="modal-title" id="confirmTitle">{{ __('driver.confirm_delete') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" id="confirmMessage">
                {{ __('driver.confirm_delete_message') }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('driver.cancel') }}
                </button>

                <button type="button" class="btn btn-danger" id="confirmBtn">
                    {{ __('driver.delete') }}
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    let formToSubmit = null;
    let actionUrl = null;

    const tDeleteItem = @json(__('driver.confirm_delete_item', ['item' => ':item']));
    const tRestoreItem = @json(__('driver.confirm_restore_item', ['item' => ':item']));
    const tDefaultMsg = @json(__('driver.confirm_delete_message'));

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;

        e.preventDefault();

        formToSubmit = btn.closest('form');
        actionUrl = btn.href || null;

        const action = btn.dataset.action || 'delete';
        const item = btn.dataset.item || '';
        let message = btn.dataset.message || '';

        if (!message) {
            if (item) {
                message = (action === 'restore') ?
                    tRestoreItem.replace(':item', item) :
                    tDeleteItem.replace(':item', item);
            } else {
                message = tDefaultMsg;
            }
        }

        // UI
        const header = document.getElementById('confirmModalHeader');
        const title = document.getElementById('confirmTitle');
        const msgEl = document.getElementById('confirmMessage');
        const okBtn = document.getElementById('confirmBtn');

        msgEl.innerText = message;

        if (action === 'restore') {
            header.className = 'modal-header bg-success text-white';
            title.innerText = '{{ __('driver.confirm_delete') }}';
            okBtn.className = 'btn btn-success';
            okBtn.innerText = '{{ __('driver.restore') }}';
        } else {
            header.className = 'modal-header bg-danger text-white';
            title.innerText = '{{ __('driver.confirm_delete') }}';
            okBtn.className = 'btn btn-danger';
            okBtn.innerText = '{{ __('driver.delete') }}';
        }

        $('#confirmModal').modal('show');
    });

    document.getElementById('confirmBtn').addEventListener('click', function() {
        if (formToSubmit) return formToSubmit.submit();
        if (actionUrl) return window.location.href = actionUrl;
    });
</script>
