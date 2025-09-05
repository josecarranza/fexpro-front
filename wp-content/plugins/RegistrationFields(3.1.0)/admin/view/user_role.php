<div class="container bootstrap-iso">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <h3 id="head"><?php echo esc_html('Roles', 'eorf'); ?> </h3>
            </div>
        </div>
        <div class="row">
            <form method="POST" enctype="multipart/form-data" id="Form">
            <div class="col-sm-12 col-md-4">
                <h4 id="Collections"><?php echo esc_html('Add New Role', 'eorf'); ?> </h4>

                <label><?php echo esc_html('Name*', 'eorf'); ?></label>
                <input type="text" id="C_Name" value="" name="role_name" class="form-control" required>
                <p id="comment"><?php echo esc_html('The name is how it appears on your site.', 'eorf'); ?></p>


                <label><?php echo esc_html('Slug*', 'eorf'); ?></label>
                <input type="text" name="role_slug" value="" id="C_Slug" class="form-control" required>
                <p id="comment"><?php echo esc_html('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'eorf'); ?></p>

                <label><?php echo esc_html('Capability*', 'eorf'); ?></label>

                <select name="capaility" class="form-control" id="cap">
                    <?php
                    global $wp_roles;
                    $all_roles = $wp_roles->roles;
                    foreach ($all_roles as $key => $item){
                        if(!in_array($key,get_option('custom_role'))){
                            ?>
                            <option value="<?php echo esc_attr($key);?>" class="cap"> <?php echo esc_attr(preg_replace('/[^A-Za-z0-9\-]/', ' ', $key));?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <p id="comment"><?php echo esc_html('Please choose the capability of user role.', 'eorf'); ?></p>

                <label><?php echo esc_html('Description*', 'eorf'); ?></label>
                <textarea class="form-control" rows="5" id="C_Description" cols="40" name="role_des"></textarea>
                <p id="comment"><?php echo esc_html('The description is not prominent by default; however, some themes may show it.', 'eorf'); ?></p>
                <input type="submit" name="save_custom_role"  value="Save Role" class="btn btn-primary"/>
            </div>

            </form>
            <form action="" method="post">

            <div class="col-md-8" id="setting">
                <label for="bulk-action-selector-top" class="screen-reader-text"><?php echo esc_html('Select bulk action','eorf')?></label>
                <select name="action" id="bulk-action-selector-top">
                    <option value=""><?php echo esc_html('Bulk Actions','eorf')?></option>
                    <option value="delete_custom_role"><?php echo esc_html('Delete','eorf')?></option>
                </select>
                <input type="submit" id="doaction" name="delete_role" class="button action" value="Apply">
                <p id="collect"> <?php echo esc_attr(count(get_option('custom_role',true)))?> <?php echo esc_html('items','eorf')?></p>
                <div class="table-responsive" id="table">
                    <table id="mytable" class="table table-bordred table-striped">

                        <thead>

                        <th><input type="hidden" value="0" name="abc">
                            <input type="checkbox" name="abc" value="1" class="check-all" id="checkAll"
                                   onclick="Check_all_checkbox();"></th>
                        <th><?php echo esc_html('Name','eorf')?></th>
                        <th><?php echo esc_html('Description','eorf')?></th>
                        <th><?php echo esc_html('Slug','eorf')?></th>


                        </thead>

                        <tbody id="FetchData">
                        <?php
                        $custom_role_slug = get_option('custom_role',true);
                        $roles_arr = get_option('wp_user_roles');
                        global $wp_roles;
                        $roles_arr = $wp_roles->roles;
                        if (count($custom_role_slug) == 0) { ?>
                            <tr id="R_comment">
                                <td>
                                    <h6><?php echo esc_html('No Custom Role Found!.', 'eorf'); ?></h6>
                                </td>
                            </tr>

                        <?php } else {

                            foreach ($roles_arr as $key=> $value) {
                                if (in_array($key,$custom_role_slug)) {
                                    ?>
                                    <tr id="R">
                                        <td class="checkboxes">
                                            <input type="checkbox" name="role_checkbox[]" value="<?php echo $key?>"
                                                   id="check">
                                        </td>
                                        <td><?php echo esc_attr($value['name'])?></td>
                                        <td><?php echo esc_attr(get_option($key))?></td>
                                        <td><?php echo esc_attr($key)?></td>
                                    </tr>

                                    <?php
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
            </div>
            </form>


</div>