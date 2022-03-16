{% extends 'layout/layout-back-office-crud.html.twig' %}

{% block title %}{{ 'page.admin.crud.<?= $entity_twig_var_singular ?>.index.title.label'|trans }}{% endblock %}

{% block inner_crud_body %}

    {% include 'back/components/partials/crud-fixed-header.html.twig' with {
        'title_page': 'page.admin.crud.<?= $entity_twig_var_singular ?>.index.title.label',
        'add_button': absolute_url(path('<?= $route_name ?>_new')),
        breadcrumb: {
            'type': 'back',
            'items': [
                'page.admin.crud.<?= $entity_twig_var_singular ?>.index.title.label'
            ]
        }
    } %}

<div class="crud-content">
    <table class="table">
        <thead>
        <tr>
            <?php foreach ($entity_fields as $field): ?>
                <th>{{ 'entity.<?= $entity_twig_var_singular ?>.<?= strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', ucfirst($field['fieldName']))) ?>.label'|trans }}</th>
            <?php endforeach; ?>
            <th>{{ 'page.admin.crud.global.action.label'|trans }}</th>
        </tr>
        </thead>
        <tbody>
        {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
        <tr>
            <?php foreach ($entity_fields as $field): ?>
                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
            <?php endforeach; ?>
            <td>
                {% include 'back/components/partials/crud-action-td.html.twig' with {
                'see_button': absolute_url(path('<?= $route_name ?>_show', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>})),
                'edit_button': absolute_url(path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>})),
                'delete_partial': {
                    'partial': '<?= $entity_twig_var_singular ?>/_delete_form.html.twig',
                    'ref': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>
                    }
                } %}
            </td>
        </tr>
        {% else %}
        <tr>
            <td colspan="6">{{ 'page.admin.crud.global.no_records.label'|trans }}</td>
        </tr>
        {% endfor %}
        </tbody>
    </table>
    <div class="pagination">
        {{ knp_pagination_render(<?= $entity_twig_var_plural ?>) }}
    </div>
</div>
{% endblock %}