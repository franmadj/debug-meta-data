
<link href="<?php echo FM_DEBUG_META_PLUGIN_URL; ?>/jquery-ui/jquery-ui.css" rel="stylesheet">
<link href="<?php echo FM_DEBUG_META_PLUGIN_URL; ?>/css/style.css" rel="stylesheet">



<div id="wrap">


    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Users</a></li>
            <li><a href="#tabs-2">Posts</a></li>

        </ul>
        <div id="tabs-1">
            <div class="styled-select slate">
                <select id="select-user">
                    <option value="">Select</option>
                    <?php echo $user_select; ?>
                </select>
            </div>
            <div></div>

            <table style="margin-top:20px;" class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>

                        <th class="dm-column" scope="col">
                            <span class="dm-column-title">Meta Key</span>
                        </th>
                        <th class="dm-column"  scope="col">
                            <span class="dm-column-title">Meta Value</span>
                        </th>

                    </tr>
                </thead>

                <tbody class="content-select-user">

                   




                <tfoot>
                    <tr>

                        <th class="dm-column" scope="col">
                            <span class="dm-column-title">Meta Key</span>
                        </th>
                        <th class="dm-column"  scope="col">
                            <span class="dm-column-title">Meta Value</span>
                        </th>

                    </tr>

                </tfoot>

            </table>



        </div>
        <div id="tabs-2">

            <div class="styled-select slate">
                <select id="select-post">
                    <option value="">Select</option>
                    <?php echo $post_select; ?>
                </select>
            </div>
            <table style="margin-top:20px;" class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>

                        <th class="dm-column" scope="col">
                            <span class="dm-column-title">Meta Key</span>
                        </th>
                        <th class="dm-column"  scope="col">
                            <span class="dm-column-title">Meta Value</span>
                        </th>

                    </tr>
                </thead>

                <tbody class="content-select-post">

                   




                <tfoot>
                    <tr>

                        <th class="dm-column" scope="col">
                            <span class="dm-column-title">Meta Key</span>
                        </th>
                        <th class="dm-column"  scope="col">
                            <span class="dm-column-title">Meta Value</span>
                        </th>

                    </tr>

                </tfoot>

            </table>
            

        </div>

    </div>




</div>



<script src="<?php echo FM_DEBUG_META_PLUGIN_URL; ?>/jquery-ui/jquery-ui.js"></script>
<script>
    jQuery(document).ready(function ($) {
        $('#select-user, #select-post').change(function () {
            var type = $(this).attr('id');
            $.post(ajaxurl, {action: 'get_meta_data', id: $(this).val(), type: type}, function (response) {
                $('.content-' + type).html(response);
                $('.expand').change(function() {
                    $(this).closest('td').find('.css-treeview input[type="checkbox"]').attr('checked',$('#expand').is(':checked'));
                });

            });

        });


        $("#tabs").tabs();




    });




</script>

