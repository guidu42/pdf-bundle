{% extends 'layout/layout-back-office-crud.html.twig' %}

{% block title %}{{ 'page.admin.crud.<?= $entity_twig_var_singular ?>.show.title.label'|trans }}{% endblock %}

{% block inner_crud_body %}

    {% include 'back/components/partials/crud-fixed-header.html.twig' with {
        'title_page': 'page.admin.crud.<?= $entity_twig_var_singular ?>.show.title.label',
        'edit_button': absolute_url(path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>})),
        'delete_partial': {
            'partial': '<?= $entity_twig_var_singular ?>/_delete_form.html.twig',
            'ref': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>
        },
        breadcrumb: {
        'type': 'back',
        'items': [
            {'name': 'page.admin.crud.<?= $entity_twig_var_singular ?>.index.title.label', 'linkSymf': '<?= $route_name ?>_index'},
            'page.admin.crud.<?= $entity_twig_var_singular ?>.show.title.label'
            ]
        }
    } %}
    <div class="crud-content">
        <table class="table">
            <tbody>
            <?php foreach ($entity_fields as $field): ?>
                <tr>
                    <th>{{ 'entity.<?= $entity_twig_var_singular ?>.<?= strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', ucfirst($field['fieldName']))) ?>.label'|trans }}</th>
                    <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
{% endblock %}