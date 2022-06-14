<?php

$num_of_comments = intval(get_comments_number());
?>

<span class="lae-comments"><i class="fas fa-comment"></i> <?php

    if (0 == $num_of_comments && !comments_open() && !pings_open()) {
        echo sprintf(esc_html__('Comments Disabled', 'livemesh-el-addons'), number_format_i18n($num_of_comments));
		
    }
    elseif (0 == $num_of_comments)
        echo sprintf(esc_html__('Sin Comentarios', 'livemesh-el-addons'), number_format_i18n($num_of_comments));
    elseif (1 == $num_of_comments)
        echo sprintf(esc_html__('%1$s Comentario', 'livemesh-el-addons'), number_format_i18n($num_of_comments));
    elseif (1 < $num_of_comments)
        echo sprintf(esc_html__('%1$s Comentarios', 'livemesh-el-addons'), number_format_i18n($num_of_comments));

    ?></span>