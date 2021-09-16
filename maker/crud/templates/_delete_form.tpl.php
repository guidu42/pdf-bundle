<form method="post" data-form-delete-model action="{{ path('<?= $route_name ?>_delete', {'<?= $entity_identifier ?>': delete_partial.ref}) }}">
    <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ delete_partial.ref) }}">
    <button class="btn-standard">{{ "page.admin.crud.global.modal.delete.button.delete.label"|trans }}</button>
</form>
