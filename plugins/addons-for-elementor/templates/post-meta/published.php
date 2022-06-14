<?php

if (empty($format))
$format = get_option('date_format');

?>

<span class="published">
    <span title="<?php echo sprintf(get_the_time(esc_html__('l, F, Y, g:i a', 'livemesh-el-addons'))); ?>"> <i class="far fa-calendar-alt"></i>  <?php echo sprintf(get_the_time($format)); ?></span>
</span>
