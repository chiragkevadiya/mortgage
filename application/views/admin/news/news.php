<ol class="breadcrumb bc-3" >
    <li>
        <a href="<?php echo site_url('admin/index') ?>"><i class="fa fa-home"></i>Home</a>
    </li>
    <li class="active">
        <strong><?php echo $title ?></strong>
    </li>
</ol>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-default"><strong><?php echo $title ?></strong></div>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="form-group form-horizontal">
            <label class="col-sm-4 control-label">Show</label>

            <div class="col-sm-8">
                <select class="form-control input-sm filter" id="showr">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group form-horizontal">
            <label class="col-sm-4 control-label">Search</label>

            <div class="col-sm-8">
                <input type="text" id="search" placeholder="Search" class="form-control input-sm" />
            </div>
        </div>
    </div>
    <div class="col-md-7 text-right">
        <a href="<?php echo site_url('news/addnew') ?>" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
    </div>
</div>

<br />

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>News Image</th>
                    <th>Title</th>
                    <th>News Date</th>
                    <th>Time</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody id="userdata"></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    jQuery(function ($) {
        fetch_data(0);

        $(document).on('click', '.pagination a', function (e) {
            if (!$(this).parent().hasClass('active'))
            {
                e.preventDefault();
                var page = ($(this).attr("href")).split('/');
                var page_number = page[page.length - 1];
                if (page_number)
                    fetch_data(page_number);
                else {
                    fetch_data(0);
                }
            }

            return false;
        });

        $('.filter').change(function () {
            fetch_data(0);
        });

        $('#search').keyup(function () {
            fetch_data(0);
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
            if (!confirm("Are you sure want to delete?")) {
                return false;
            }

            $.ajax({
                type: "POST",
                data: {'id': id},
                url: '<?php echo site_url('news/delete_news') ?>',
                dataType: 'json',
                beforeSend: function () {
                    run_waitMe();
                },
                success: function (res) {
                    if (res.status)
                    {
                        show_toast(res.message, true);
                        fetch_data(0);
                    } else {
                        show_toast(res.data.message, true);
                    }
                },
                error: function (xhr) {
                    show_toast(xhr.statusText + xhr.responseText, false);
                },
                complete: function () {
                    hide_waitMe();
                }
            });
        });
    });

    function fetch_data(id)
    {
        var page = parseInt(id) + 1;
        var search = $('#search').val();
        var showr = $('#showr').val();

        if (id === '') {
            id = 0;
        }

        $.ajax({
            type: "POST",
            url: '<?php echo site_url('news/get_news/') ?>' + id,
            data: {search: search, perpage: showr},
            beforeSend: function () {
                run_waitMe();
            },
            success: function (res) {
                var j = parseInt(id);
                if (res.status)
                {
                    var userdata = '';
                    var holder = res.data;
                    var length = holder.length;
                    for (var i = 0; i < length; i++)
                    {
                        var perpage = parseInt($('#showr').val());
                        var showing = perpage + page - 1;
                        if ((perpage) > res.total_rows)
                        {
                            showing = perpage;
                        }

                        if (showing > res.total_rows)
                        {
                            showing = res.total_rows;
                        }

                        var delete_btn = '<button data-id="' + holder[i].newsid + '" class="btn btn-danger btn-xs delete"><i class="fa fa-times"></i></button>';

                        userdata += '<tr class="">' +
                                '<td>' + parseInt(j + 1) + '</td>' +
                                '<td><img class="news_image img-thumbnail" src="' + holder[i].file_path + '" onerror="this.src=\'../assets_admin/images/default.png\'" alt="Profile"></td>' +
                                '<td>' + holder[i].title + '</td>' +
                                '<td>' + holder[i].news_date + '</td>' +
                                '<td>' + holder[i].news_time + '</td>' +
                                '<td class="text-right">' + delete_btn + '</td>' +
                                '</tr>';
                        j++;
                    }

                    var pagination = '';
                    if (res.links)
                    {
                        pagination = res.links;
                    }

                    userdata += '<tr><td colspan="3">Showing ' + page + ' to ' + showing + ' of ' + res.total_rows + ' entries</td>' +
                            '<td class="text-right" colspan="3">' + pagination + '</td></tr>';
                } else {
                    userdata += '<tr><td colspan="6">No records found.</td></tr>';
                }

                $("#userdata").html(userdata);
            },
            error: function (xhr) {
                show_toast(xhr.statusText + xhr.responseText, false);
            },
            complete: function () {
                hide_waitMe();
            }
        });
    }
</script>