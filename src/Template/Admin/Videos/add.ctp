<?php
/**
 * This file is part of me-cms-youtube.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-cms-youtube
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
$this->extend('MeCms./Admin/Common/form');
$this->assign('title', $title = __d('me_cms_youtube', 'Add video'));
$this->Library->datetimepicker();
?>

<div class="card card-body bg-light border-0 mb-4">
    <?= $this->Form->createInline(null, ['type' => 'get']) ?>
    <fieldset>
    <?php
        echo $this->Form->label('url', __d('me_cms_youtube', 'Video url'));
        echo $this->Form->control('url', [
            'default' => $this->request->getQuery('url'),
            'label' => __d('me_cms_youtube', 'Video url'),
            'name' => 'url',
            'onchange' => 'send_form(this)',
            'size' => 100,
        ]);
        echo $this->Form->submit(I18N_SELECT, ['div' => false]);
    ?>
    </fieldset>
    <?= $this->Form->end() ?>
</div>

<?php if ($this->request->getData('youtube_id')) : ?>
    <?= $this->Form->create($video); ?>
    <div class="row">
        <div class="col-lg-3 order-12">
            <div class="float-form">
            <?php
            //Only admins and managers can add videos on behalf of other users
            if ($this->Auth->isGroup(['admin', 'manager'])) {
                echo $this->Form->control('user_id', [
                    'default' => $this->Auth->user('id'),
                    'label' => I18N_AUTHOR,
                ]);
            }

            echo $this->Form->control('category_id', [
                'default' => count($categories) < 2 ? collection($categories)->first() : false,
                'label' => I18N_CATEGORY,
            ]);
            echo $this->Form->datetimepicker('created', [
                'label' => I18N_DATE,
                'help' => [
                    I18N_USE_CURRENT_DATETIME,
                    I18N_DELAY_PUBLICATION,
                ],
            ]);
            echo $this->Form->control('priority', [
                'default' => '3',
                'label' => I18N_PRIORITY,
            ]);
            echo $this->Form->control('is_spot', [
                'label' => sprintf('%s?', __d('me_cms_youtube', 'Is a spot')),
                'help' => __d('me_cms_youtube', 'Enable this option if this video is a spot'),
            ]);
            echo $this->Form->control('active', [
                'checked' => true,
                'label' => I18N_PUBLISHED,
                'help' => I18N_HELP_DRAFT,
            ]);
            ?>
            </div>
        </div>
        <fieldset class="col-lg-9">
            <div class="row mb-4 text-center">
                <div class="col-sm-6">
                    <h4><?= __d('me_cms_youtube', 'Video') ?></h4>
                    <?= $this->Html->youtube(
                        $this->request->getData('youtube_id'),
                        ['class' => 'mx-auto', 'height' => 315, 'width' => 560]
                    ) ?>
                </div>
                <div class="col-sm-6">
                    <h4><?= __d('me_cms_youtube', 'Thumbnail preview') ?></h4>
                    <?= $this->Thumb->resize(
                        $this->request->getData('preview'),
                        ['height' => 315],
                        ['class' => 'mx-auto']
                    ) ?>
                </div>
            </div>
            <p>
                <?= $this->Html->link(
                    __d('me_cms_youtube', 'Open on {0}', 'YouTube'),
                    $this->request->getData('youtube_url'),
                    ['icon' => 'external-link', 'target' => '_blank']
                ) ?>
            </p>
            <?php
                echo $this->Form->control('youtube_id', [
                    'label' => __d('me_cms_youtube', '{0} ID', 'YouTube'),
                    'readonly' => true,
                    'type' => 'text',
                    'value' => $this->request->getData('youtube_id'),
                ]);
                echo $this->Form->control('duration', [
                    'label' => __d('me_cms_youtube', 'Duration'),
                    'readonly' => true,
                ]);
                echo $this->Form->control('title', [
                    'label' => I18N_TITLE,
                ]);
                echo $this->Form->control('subtitle', [
                    'label' => I18N_SUBTITLE,
                ]);
                echo $this->Form->control('text', [
                    'label' => I18N_TEXT,
                    'rows' => 8,
                ]);
            ?>
        </fieldset>
    </div>
    <?= $this->Form->submit($title) ?>
    <?= $this->Form->end() ?>
<?php endif; ?>
