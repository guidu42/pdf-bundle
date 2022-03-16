{% extends 'layout/layout-back-office-crud.html.twig' %}

{% block title %}{{ 'page.admin.crud.<?= strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $entity_class_name)) ?>.new.title.label'|trans }}{% endblock %}

{% block inner_crud_body %}

    {% include 'back/components/partials/crud-fixed-header.html.twig' with {
        'title_page': 'page.admin.crud.<?= strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $entity_class_name)) ?>.new.title.label',
        'save_form': {
            'type': 'add',
            'ref': ''
        },
        breadcrumb: {
            'type': 'back',
            'items': [
                {'name': 'page.admin.crud.<?= strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $entity_class_name)) ?>.index.title.label', 'linkSymf': '<?= $route_name ?>_index'},
                'page.admin.crud.<?= strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $entity_class_name)) ?>.new.title.label'
            ]
        }
    } %}
    <div class="crud-content">
        {{ include('<?= $templates_path ?>/_form.html.twig') }}
    </div>
{% endblock %}