<div class="ptcs_box">
    <style scoped>
        .ptcs_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .ptcs_field{
            display: contents;
        }
    </style>
    <p class="meta-options ptcs_field">
        <label for="post_custom_colors">Select Post Title Color</label>
        <input id="post_custom_colors" class="my-color-field" type="text" name="post_custom_colors" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'post_custom_colors', true ) ); ?>">
    </p>
</div>