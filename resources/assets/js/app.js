(function ($) {
    $('#project').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var project_id = button.data('project-id');

        var modal = $(this);

        var title = 'Add a new project';
        $('.btn-danger', modal).hide();
        $('.callout-danger', modal).hide();
        $('.has-error', modal).removeClass('has-error');

        var project = {
            id: '',
            name: '',
            repository: '',
            branch: '',
            builds_to_keep: 10,
            url: '',
            build_url: ''
        };

        if (project_id) {
            title = 'Edit project';

            var project = $.grep(projects, function(element) {
                return element.id == project_id;
            });

            project = project[0];

            $('.btn-danger', modal).show();
        }

        $('#project_id').val(project.id);
        $('#project_name').val(project.name);
        $('#project_repository').val(project.repository);
        $('#project_branch').val(project.branch);
        $('#project_builds_to_keep').val(project.builds_to_keep);
        $('#project_url').val(project.url);
        $('#project_build_url').val(project.build_url);

        modal.find('.modal-title span').text(title);
    });

    $('#project button.btn-delete').on('click', function (event) {
        var target = $(event.currentTarget);
        var icon = target.find('i');
        var dialog = target.parents('.modal');

        var id = $('form input[name="id"]', dialog).val();

        icon.addClass('fa-refresh fa-spin').removeClass('fa-trash');
        dialog.find('input').attr('disabled', 'disabled');
        $('button.close', dialog).hide();

        $.ajax({
            url: '/projects/' + id,
            type: 'DELETE',
            beforeSend: function (request) {
                request.setRequestHeader('X-CSRF-Token', $('meta[name="token"]').attr('content'));
            }
        }).done(function(data) {
            dialog.modal('hide');
            $('.callout-danger', dialog).hide();

            window.location.href = '/';
        }).fail(function(response) {
            // FIXME: Do something here
        }).always(function() {
            icon.removeClass('fa-refresh fa-spin').addClass('fa-trash');
            $('button.close', dialog).show();
            dialog.find('input').removeAttr('disabled');
        });
    });

    $('#project button.btn-save').on('click', function (event) {
        var target = $(event.currentTarget);
        var icon = target.find('i');
        var dialog = target.parents('.modal');

        var fields = $('form', dialog).serialize();

        var url = '/projects';
        var id = $('form input[name="id"]', dialog).val();
        var method = 'POST';

        if (id !== '') {
            url +=  '/' + id;
            method = 'PUT';
        }

        icon.addClass('fa-refresh fa-spin').removeClass('fa-save');
        dialog.find('input').attr('disabled', 'disabled');
        $('button.close', dialog).hide();

        $.ajax({
            url: url,
            type: method,
            data: fields,
            beforeSend: function (request) {
                request.setRequestHeader('X-CSRF-Token', $('meta[name="token"]').attr('content'));
            }
        }).done(function(data) {
            dialog.modal('hide');
            $('.callout-danger', dialog).hide();

            if (method === 'POST') {
                window.location.href = '/projects/' + data.project.id;
            } else if (method === 'DELETE') {
                window.location.href = '/';
            }
        }).fail(function(response) {
            $('.callout-danger', dialog).show();

            var errors = response.responseJSON.errors;

            $('form input', dialog).each(function (index, element) {
                element = $(element);

                var name = element.attr('name');

                if (typeof errors[name] != 'undefined') {
                    element.parent('div').addClass('has-error');
                }
            });
        }).always(function() {
            icon.removeClass('fa-refresh fa-spin').addClass('fa-save');
            $('button.close', dialog).show();
            dialog.find('input').removeAttr('disabled');
        });
    });

    $('#user').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var user_id = button.data('user-id');

        var modal = $(this);

        var title = 'Add a new user';
        $('.btn-danger', modal).hide();
        $('.callout-danger', modal).hide();
        $('.has-error', modal).removeClass('has-error');

        var user = {
            id: '',
            name: '',
            email: ''
        };

        if (user_id) {
            title = 'Edit user';

            var user = $.grep(users, function(element) {
                return element.id == user_id;
            });

            user = user[0];

            $('.btn-danger', modal).show();
        }

        $('#user_id').val(user.id);
        $('#user_name').val(user.name);
        $('#user_email').val(user.email);

        modal.find('.modal-title span').text(title);
    });

    $('#new_webhook').on('click', function(event) {
        var target = $(event.currentTarget);
        var project_id = target.data('project-id');
        var icon = $('i', target);

        if ($('.fa-spin', target).length > 0) {
            return;
        }

        target.attr('disabled', 'disabled');

        icon.addClass('fa-spin');

        $.ajax({
            type: 'GET',
            url: '/webhook/' + project_id + '/refresh'
        }).fail(function (response) {

        }).done(function (data) {
            $('#webhook').html(data.url);
        }).always(function () {
            icon.removeClass('fa-spin');
            target.removeAttr('disabled');
        });
    });
})(jQuery);