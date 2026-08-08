<?php
if (post_password_required()) {
    return;
}
?>
<section id="comments" class="scouting-nederland-comments" aria-labelledby="comments-title">
    <?php if (have_comments()) : ?>
        <h2 id="comments-title" class="scouting-nederland-comments-title">
            <?php
            $scouting_nederland_comment_count = get_comments_number();
            printf(
                esc_html(_n('%s reactie', '%s reacties', $scouting_nederland_comment_count, 'scouting-nederland')),
                esc_html(number_format_i18n($scouting_nederland_comment_count))
            );
            ?>
        </h2>

        <ol class="scouting-nederland-comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
            ));
            ?>
        </ol>

        <?php
        the_comments_pagination(array(
            'prev_text' => esc_html__('Oudere reacties', 'scouting-nederland'),
            'next_text' => esc_html__('Nieuwere reacties', 'scouting-nederland'),
        ));
        ?>
    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number()) : ?>
        <p class="scouting-nederland-comments-closed"><?php esc_html_e('Reacties zijn gesloten.', 'scouting-nederland'); ?></p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'class_form'         => 'scouting-nederland-comment-form',
        'title_reply'        => esc_html__('Laat een reactie achter', 'scouting-nederland'),
        'title_reply_before' => '<h2 id="reply-title" class="scouting-nederland-comments-title comment-reply-title">',
        'title_reply_after'  => '</h2>',
        'label_submit'       => esc_html__('Reactie plaatsen', 'scouting-nederland'),
    ));
    ?>
</section>
