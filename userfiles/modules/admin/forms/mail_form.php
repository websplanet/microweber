<? $rand_id = md5(serialize($params)); ?>
<script type="text/javascript">
                $(document).ready(function(){
                 	$("#mw_email_form_editor<? print $rand_id ?>").accordion({
			autoHeight: false,
			clearStyle: true,
			collapsible: true,
				 
				animated: false,
				icons: { header: "ui-icon-triangle-1-w",
			headerSelected: "ui-icon-triangle-1-s" },
				navigation: true
										   
									   
		})
                });
            </script>

<div id="mw_email_form_editor<? print $rand_id ?>">
  <h3><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/12-eye.png"  width="24" class="css_editor_accordeon_icon" />Form settings</a></h3>
  <div>
    <div class="mw_tag_editor_item_holder">
      <table border="0" cellspacing="5" cellpadding="0" >
        <tr valign="middle">
          <td><div class="mw_tag_editor_label_wide">Form Title</div>
            <input name="form_title" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="forms/mail_form"  value="<?php print option_get('form_title', $params['module_id']) ?>" /></td>
        </tr>
        <tr valign="middle">
          <td><label>Form Description</label>
            <br />
            <textarea name="form_description" cols=""  class="mw_option_field mw_tag_editor_textarea" refresh_modules="forms/mail_form"   option_group="<? print $params['module_id'] ?>" rows="2"><?php print option_get('media_description', $params['module_id']) ?></textarea></td>
        </tr>
        <tr valign="middle">
          <td><label>Your email (to receive the form)</label>
            <br />
            <input name="form_email" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="forms/mail_form"  value="<?php print option_get('media_name', $params['module_id']) ?>" /></td>
        </tr>
      </table>
    </div>
  </div>
  <h3><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/12-eye.png"  width="24" class="css_editor_accordeon_icon" />Form fields</a></h3>
  <div>
    <div class="mw_tag_editor_item_holder">
      <microweber module="admin/content/custom_fields" for_module_id="<? print $params['module_id'] ?>" />
    </div>
  </div>
  <h3><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/55-network.png"  width="24" class="css_editor_accordeon_icon" />After send</a></h3>
  <div>
    <div class="mw_tag_editor_item_holder">
      <table border="0" cellspacing="5" cellpadding="0" >
        <tr valign="middle">
          <td><div class="mw_tag_editor_label_wide">After submit</div>
           
           
           
             <select name="after_send" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="forms/mail_form" >
            <option value="" <? if( trim(option_get('after_send', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >Do nothing</option>
            <option value="go_to_page" <? if( option_get('after_send', $params['module_id']) == '1') : ?>  selected="selected" <? endif; ?> >Go to page</option>
            <option value="after_send_email" <? if( option_get('after_send', $params['module_id']) == '2') : ?>  selected="selected" <? endif; ?> >Send confirmation email</option>
             
          </select>
           
           
           
           
           
           
           
           
           
           
           
           
            </td>
        </tr>
        <tr valign="middle">
          <td><label>Responce text</label>
            <br />
            
            
            
            
             <textarea name="form_description" cols=""  class="mw_option_field mw_tag_editor_textarea" refresh_modules="forms/mail_form"   option_group="<? print $params['module_id'] ?>" rows="2"><?php print option_get('form_description', $params['module_id']) ?></textarea>
            
            
            
     </td>
        </tr>
        <tr valign="middle">
          <td><label>Redirect to page (after submit)</label>
            <br />
            <input name="go_to_page_after_submit" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="forms/mail_form"  value="<?php print option_get('go_to_page_after_submit', $params['module_id']) ?>" /></td>
        </tr>
      </table>
    </div>
  </div>
</div>
