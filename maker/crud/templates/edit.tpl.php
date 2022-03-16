{% extends 'layout/layout-back-office-crud.html.twig' %}

{% block title %}{{ 'page.admin.crud.<?= $entity_twig_var_singular ?>.edit.title.label'|trans }}{% endblock %}


{% block inner_crud_body %}

    {% include 'back/components/partials/crud-fixed-header.html.twig' with {
        'title_page': 'page.admin.crud.<?= $entity_twig_var_singular ?>.edit.title.label',
        'delete_partial': {
            'partial': '<?= $entity_twig_var_singular ?>/_delete_form.html.twig',
            'ref': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>
        },
        'save_form': {
            'type': 'edit',
            'ref': ''
        },
        breadcrumb: {
            'type': 'back',
            'items': [
                {'name': 'page.admin.crud.<?= $entity_twig_var_singular ?>.index.title.label', 'linkSymf': '<?= $route_name ?>_index'},
                'page.admin.crud.<?= $entity_twig_var_singular ?>.edit.title.label'
            ]
        }
    } %}

<div class="crud-content">
    {{ include('<?= $entity_twig_var_singular ?>/_form.html.twig') }}
</div>

{% endblock %}

