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


class CSvgGraphArea extends CSvgGraphLine {

	public function __construct($path, $metric) {
		parent::__construct($path, $metric);

		$this->add_label = false;
		$this->options = $metric['options'] + [
			'fill' => 5
		];
	}

	public function getStyles() {
		$this
			->addClass(CSvgTag::ZBX_STYLE_SVG_GRAPH_AREA)
			->addClass(CSvgTag::ZBX_STYLE_SVG_GRAPH_AREA.'-'.$this->itemid.'-'.$this->options['order']);

		return [
			'.'.CSvgTag::ZBX_STYLE_SVG_GRAPH_AREA => [
				'stroke-width' => 0
			],
			'.'.CSvgTag::ZBX_STYLE_SVG_GRAPH_AREA.'-'.$this->itemid.'-'.$this->options['order'] => [
				'fill-opacity' => $this->options['fill']  * 0.1,
				'fill' => $this->options['color']
			]
		];
	}

	protected function draw() {
		$path = parent::draw();

		if ($this->path) {
			$first_point = reset($this->path);
			$last_point = end($this->path);
			$this
				->lineTo($last_point[0], $this->position_y + $this->height)
				->lineTo($first_point[0], $this->position_y + $this->height)
				->closePath();
		}

		return $path;
	}
}
