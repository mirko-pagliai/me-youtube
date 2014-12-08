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
 * @author		Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright	Copyright (c) 2014, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 * @package		MeYoutube\View\Videos
 */
?>

<?php
	$this->assign('sidebar', $this->MeYoutubeMenu->get('videos', 'nav'));
	$this->Library->datetimepicker();
?>

<div class="youtubeVideos form">
	<?php echo $this->Html->h2(__d('me_youtube', 'Edit video')); ?>
	<?php echo $this->Form->create('Video'); ?>
		<div class='float-form'>
			<?php
				//Only admins and managers can add posts on behalf of other users
				if($this->Auth->isManager())
					echo $this->Form->input('user_id', array(
						'label' => __d('me_cms', 'Author')
					));
				
				echo $this->Form->input('category_id', array(
					'label' => __d('me_cms', 'Category')
				));
				echo $this->Form->datetimepicker('created', array(
					'label'	=> __d('me_cms', 'Date'),
					'tip'	=> array(
						sprintf('%s.', __d('me_cms', 'If blank, the current date and time will be used')),
						sprintf('%s.', __d('me_cms', 'You can delay the publication by entering a future date'))
					),
					'value'	=> $this->Time->format($this->request->data['Video']['created'], '%Y-%m-%d %H:%M')
				));
				echo $this->Form->input('priority', array(
					'default'	=> '3',
					'label'		=> __d('me_cms', 'Priority'),
					'options'	=> array(
						'1' => sprintf('1 - %s', __d('me_cms', 'Very low')),
						'2' => sprintf('2 - %s', __d('me_cms', 'Low')),
						'3' => sprintf('3 - %s', __d('me_cms', 'Normal')),
						'4' => sprintf('4 - %s', __d('me_cms', 'High')),
						'5' => sprintf('5 - %s', __d('me_cms', 'Very high')),
					)
				));
				echo $this->Form->input('is_spot', array(
					'label'	=> sprintf('%s?', __d('me_youtube', 'Is a spot')),
					'tip'	=> __d('me_youtube', 'Enable this option if this video is a spot')
				));
				echo $this->Form->input('active', array(
					'label'	=> sprintf('%s?', __d('me_cms', 'Published')),
					'tip'	=> __d('me_cms', 'Disable this option to save as a draft')
				));
			?>
		</div>
		<fieldset>
			<?php
				echo $this->Form->input('id');
				echo $this->Html->iframe(array(
					'class'				=> 'margin-15',
					'allowfullscreen'	=> TRUE,
					'height'			=> 315,
					'src'				=> sprintf('http://www.youtube-nocookie.com/embed/%s?rel=0&amp;showinfo=0', $this->request->data['Video']['youtube_id']),
					'width'				=> 560
				));
				echo $this->Form->input('youtube_id', array(
					'label'		=> __d('me_youtube', '%s ID', 'YouTube'),
					'readonly'	=> TRUE,
					'type'		=> 'text',
					'value'		=> $this->request->data['Video']['youtube_id']
				));
				echo $this->Form->input('title', array(
					'label' => __d('me_cms', 'Title'),
				));
				echo $this->Form->input('subtitle', array(
					'label' => __d('me_cms', 'Subtitle'),
				));
				echo $this->Form->input('description', array(
					'label' => __d('me_cms', 'Description'),
					'rows'	=> 2,
					'type'	=> 'textarea'
				));
			?>
		</fieldset>
	<?php echo $this->Form->end(__d('me_youtube', 'Edit video')); ?>
</div>