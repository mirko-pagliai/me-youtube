<?php
/**
 * This file is part of MeYoutube.
 *
 * MeYoutube is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * MeYoutube is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with MeYoutube.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */

$this->extend('MeCms./Admin/Common/form');
$this->assign('title', $title = __d('me_cms', 'Add video'));
$this->Library->datetimepicker();
?>

<div class="well">
    <?php
        echo $this->Form->createInline(false, ['type' => 'get']);
        echo $this->Form->label('url', __d('me_youtube', 'Video url'));
        echo $this->Form->input('url', [
            'default' => $this->request->query('url'),
            'label' => __d('me_youtube', 'Video url'),
            'name' => 'url',
            'onchange' => 'send_form(this)',
            'size' => 100,
        ]);
        echo $this->Form->submit(__d('me_cms', 'Select'), ['div' => false]);
        echo $this->Form->end();
    ?>
</div>

<?php if ($this->request->data('youtubeId')) : ?>
    <?= $this->Form->create($video); ?>
    <div class='float-form'>
        <?php
        //Only admins and managers can add videos on behalf of other users
        if ($this->Auth->isGroup(['admin', 'manager'])) {
            echo $this->Form->input('user_id', [
                'default' => $this->Auth->user('id'),
                'label' => __d('me_cms', 'Author'),
            ]);
        }

            echo $this->Form->input('category_id', [
                'default' => count($categories) < 2 ? firstValue($categories) : null,
                'label' => __d('me_cms', 'Category'),
            ]);
            echo $this->Form->datetimepicker('created', [
                'label' => __d('me_cms', 'Date'),
                'tip' => [
                    __d('me_cms', 'If blank, the current date and time will be used'),
                    __d('me_cms', 'You can delay the publication by entering a future date'),
                ],
            ]);
            echo $this->Form->input('priority', [
                'default' => '3',
                'label' => __d('me_cms', 'Priority'),
            ]);
            echo $this->Form->input('is_spot', [
                'label' => sprintf('%s?', __d('me_youtube', 'Is a spot')),
                'tip' => __d('me_youtube', 'Enable this option if this video is a spot'),
            ]);
            echo $this->Form->input('active', [
                'checked' => true,
                'label' => sprintf('%s?', __d('me_cms', 'Published')),
                'tip' => __d('me_cms', 'Disable this option to save as a draft'),
            ]);
        ?>
    </div>

    <fieldset>
        <div class="row margin-20 text-center">
            <div class="col-sm-6">
                <h4><?= __d('me_youtube', 'Video') ?></h4>
                <?php
                    echo $this->Html->youtube(
                        $this->request->data('youtubeId'),
                        ['class' => 'center-block', 'height' => 315, 'width' => 560]
                    );
                ?>
            </div>
            <div class="col-sm-6">
                <h4><?= __d('me_youtube', 'Thumbnail preview') ?></h4>
                <?php
                    echo $this->Thumb->image(
                        $this->request->data('preview'),
                        ['height' => 315, 'class' => 'center-block']
                    );
                ?>
            </div>
        </div>
        <p>
            <?php
                echo $this->Html->link(
                    __d('me_youtube', 'Open on {0}', 'YouTube'),
                    $this->request->data('youtubeUrl'),
                    ['icon' => 'external-link', 'target' => '_blank']
                );
            ?>
        </p>
        <?php
            echo $this->Form->input('youtube_id', [
                'label' => __d('me_youtube', '{0} ID', 'YouTube'),
                'readonly' => true,
                'type' => 'text',
                'value' => $this->request->data('youtubeId'),
            ]);
            echo $this->Form->input('duration', [
                'label' => __d('me_youtube', 'Duration'),
                'readonly' => true,
            ]);
            echo $this->Form->input('title', [
                'label' => __d('me_cms', 'Title'),
            ]);
            echo $this->Form->input('subtitle', [
                'label' => __d('me_cms', 'Subtitle'),
            ]);
            echo $this->Form->input('text', [
                'label' => __d('me_cms', 'Text'),
                'rows' => 8,
            ]);
        ?>
    </fieldset>
    <?= $this->Form->submit($title) ?>
    <?= $this->Form->end() ?>
<?php endif; ?>
