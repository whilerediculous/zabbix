<?php
/*
** Zabbix
** Copyright (C) 2001-2018 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


$widget = (new CWidget())
	->setTitle(_('Slide shows'))
	->addItem((new CList())
	->setAttribute('role', 'navigation')
	->setAttribute('aria-label', _x('Hierarchy', 'screen reader'))
	->addClass(ZBX_STYLE_OBJECT_GROUP)
	->addItem([
		(new CSpan())->addItem(new CLink(_('All slide shows'), 'slideconf.php')),
		'/',
		(new CSpan())
			->addClass(ZBX_STYLE_SELECTED)
			->addItem(
				new CLink($data['screen']['name'], (new CUrl('slides.php'))
					->setArgument('elementid', $data['screen']['slideshowid'])
					->setArgument('fullscreen', $data['fullscreen'] ? '1' : null)
				)
			)
	]));

$controls = (new CList())
	->addItem(
		new CComboBox('config', 'slides.php', 'redirect(this.options[this.selectedIndex].value);', [
			'screens.php' => _('Screens'),
			'slides.php' => _('Slide shows')
		])
	);

$favourite_icon = get_icon('favourite', [
	'fav' => 'web.favorite.screenids',
	'elname' => 'slideshowid',
	'elid' => $this->data['elementId']
]);

$refresh_icon = get_icon('screenconf');

if ($this->data['screen']) {
	$refresh_icon->setMenuPopup(CMenuPopupHelper::getRefresh(
		WIDGET_SLIDESHOW,
		'x'.$this->data['refreshMultiplier'],
		true,
		[
			'elementid' => $this->data['elementId']
		]
	));
}

if (isset($this->data['isDynamicItems'])) {
	$controls->addItem([
		new CLabel(_('Group'), 'groupid'),
		(new CDiv())->addClass(ZBX_STYLE_FORM_INPUT_MARGIN),
		$this->data['pageFilter']->getGroupsCB()
	]);
	$controls->addItem([
		new CLabel(_('Host'), 'hostid'),
		(new CDiv())->addClass(ZBX_STYLE_FORM_INPUT_MARGIN),
		$this->data['pageFilter']->getHostsCB()
	]);
}

$widget->setControls((new CList([
	(new CForm('get'))
		->setAttribute('aria-label', _('Main filter'))
		->setName('slideHeaderForm')
		->addVar('fullscreen', $data['fullscreen'] ? '1' : null)
		->addItem($controls),
	(new CTag('nav', true, (new CList())
		->addItem($data['screen']['editable']
			? (new CButton('edit', _('Edit slide show')))
				->onClick('redirect("slideconf.php?form=update&slideshowid='.$data['screen']['slideshowid'].'")')
			: null
		)
		->addItem($favourite_icon)
		->addItem($refresh_icon)
		->addItem(get_icon('fullscreen', ['fullscreen' => $data['fullscreen']]))
	))
		->setAttribute('aria-label', _('Content controls'))
])));

$filter = (new CFilter())
	->setProfile($data['timeline']['profileIdx'], $data['timeline']['profileIdx2'])
	->setActiveTab($data['active_tab'])
	->addTimeSelector($data['timeline']['from'], $data['timeline']['to']);
$widget->addItem($filter);

$widget->addItem(
	(new CDiv((new CDiv())->addClass('preloader')))
		->setId(WIDGET_SLIDESHOW)
);

require_once dirname(__FILE__).'/js/monitoring.slides.js.php';

return $widget;
