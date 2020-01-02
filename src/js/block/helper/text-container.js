import _ from 'lodash';
import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';

import * as Constants from '../../constants';

// see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-registration/
registerBlockType(Constants.BLOCK_TEXT_CONTAINER, {
  title: __('Text Container', Constants.TEXT_DOMAIN),
  category: Constants.CATEGORY,
  supports: { inserter: false },
  attributes: {
    template: {
      type: 'array',
      default: [[Constants.BLOCK_TITLE, {}], [Constants.BLOCK_TEXT, {}]],
    },
    isLocked: { type: 'boolean', default: false },
    onlyText: { type: 'boolean', default: false },
  },
  edit({ attributes }) {
    return (
      <InnerBlocks
        allowedBlocks={
          attributes.onlyText
            ? [Constants.BLOCK_TEXT]
            : [Constants.BLOCK_TITLE, Constants.BLOCK_TEXT]
        }
        template={filterTemplateIfOnlyText(attributes)}
        templateLock={
          attributes.isLocked
            ? Constants.INNER_BLOCKS_LOCKED
            : Constants.INNER_BLOCKS_UNLOCKED
        }
      />
    );
  },
  save() {
    return <InnerBlocks.Content />;
  },
});

function filterTemplateIfOnlyText(attributes) {
  return attributes.onlyText
    ? _.filter(attributes.template, spec => spec[0] == Constants.BLOCK_TEXT)
    : attributes.template;
}