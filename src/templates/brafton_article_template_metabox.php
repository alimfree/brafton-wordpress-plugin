<table> 
    <tr valign="top">
        <th class="metabox_label_column">
            <label for="brafton_id">Brafton ID</label>
        </th>
        <td>
            <input type="text" id="brafton_id" name="brafton_id" value="<?php echo @get_post_meta($post->ID, 'brafton_id', true); ?>" />
        </td>
    <tr>
    <tr valign="top">
        <th class="metabox_label_column">
            <label for="photo_id">Picture ID</label>
        </th>
        <td>
            <input type="text" id="photo_id" name="photo_id" value="<?php echo @get_post_meta($post->ID, 'photo_id', true); ?>" />
        </td>
    <tr>
</table>