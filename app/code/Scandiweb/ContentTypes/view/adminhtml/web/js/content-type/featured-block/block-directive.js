define(["Scandiweb_ContentTypes/js/content-type/block-directive"], function (
    BlockDirectiveBase
) {
    "use strict";
    const $super = BlockDirectiveBase.prototype;

    function BlockDirective(parent, config, stageId) {
        BlockDirectiveBase.call(this, parent, config, stageId);
    }

    BlockDirective.prototype = Object.create($super);

    var _proto = BlockDirective.prototype;

    _proto.getAdditionalBlockAttributes = function getAdditionalBlockAttributes(
        data
    ) {
        const {
            content_title,
            content_description,
            show_first_cta,
            first_cta_text,
            first_cta_link,
            first_cta_type,
            show_second_cta,
            second_cta_text,
            second_cta_link,
            second_cta_type,
            visual_content_position,
            visual_type,
            visual_content_img,
            visual_content_vid,
            placeholder_type,
            visual_content_video_placeholder,
            visual_content_video_placeholder_url,
        } = data || {};

        var attributes = {
            content_title,
            content_description,
            show_first_cta,
            first_cta_text,
            first_cta_link,
            first_cta_type,
            show_second_cta,
            second_cta_text,
            second_cta_link,
            second_cta_type,
            visual_content_position,
            visual_type,
            visual_content_img: JSON.stringify(visual_content_img),
            visual_content_vid,
            placeholder_type,
            visual_content_video_placeholder: JSON.stringify(visual_content_video_placeholder),
            visual_content_video_placeholder_url
        };

        return attributes;
    };

    return BlockDirective;
});
