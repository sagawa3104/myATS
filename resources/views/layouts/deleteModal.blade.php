@php
    $modalId = $modalId ?? 'deleteModal';
@endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        ID:{{ $id }}のデータを削除しますか？
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
            <button type="button" class="btn btn-primary" id="del">実行</button>
        </div>
        </div>
    </div>
</div>
{{ Form::open(['url' => $url, 'method' => 'delete', 'id' => 'deleteForm']) }}
{{ Form::close() }}

@push('deleteModalJs')
<script>
    document.querySelector('#del').addEventListener('click', function() {
        document.querySelector('#deleteForm').submit()
    })
</script>
@endpush